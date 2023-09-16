<?php

namespace Modules\V021\Http\Controllers\Auth;

use stdClass;
use App\Models\User;
use App\Models\Otp\Otpcode;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Resources\Respons;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Marketplace\UserMarket;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;
use Modules\V021\Http\Repositories\ResgiterUserRepo;

class RegisterController extends Controller
{
    protected $strleft,$repo;

    public function __construct(ResgiterUserRepo $repo)
    {
        $this->strleft = substr(Carbon::now()->timestamp, 3);
        $this->repo = $repo;
    }

    public function registerSendOtp(Request $req)
    {
        $validator = Validator::make($req->only('email'), [
            'email' => 'required|email:filter,spoof,dns|unique:users,email',
        ]);

        if ($validator->fails()) return new Respons(false, 'Validation Failed', $validator->errors());


        // GET OTP
        $validUntil = Carbon::now()->addMinute(5);
        $codeOTP = rand(1000, 9999);
        Otpcode::updateOrCreate(
            ['key' => $req->email],
            ['otp' => $codeOTP, 'tipe' => 'REGISTER', 'valid' => $validUntil, 'status' => 0],
        );

        $mailData = [
            'codeOTP' => $codeOTP,
            'title' => 'REGISTER CODE'
        ];
        // Mail::to($req->email)->send(new OTPRegister($mailData));
        return new Respons(true, 'Kode berhasil dikirim', $mailData);
    }

    public function registerConfirmOtp(Request $req)
    {
        $validator = Validator::make($req->only('key', 'otp'), [
            'key' => 'required|unique:users,email',
            'otp' => 'required',
        ]);
        if ($validator->fails()) {
            return new Respons(false, 'Validation Failed', $validator->errors());
        }

        $otp = Otpcode::where(['key' => $req->key, 'otp' => $req->otp, 'status' => 0])->first();
        if (!$otp) return new Respons(false, 'OTP Does Not Match');

        if (Carbon::now() > $otp->valid) return new Respons(false, 'OTP Timeout');

        $otp->status = 1;
        $otp->save();
        return new Respons(true, 'OTP Confirmed');
    }

    public function register(Request $req)
    {
        // Validasi
        $only = $req->only('name', 'email', 'password', 'phone');
        $validator = Validator::make($only, [
            'name' => 'required',
            'email' => 'required|email:filter,spoof,dns|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'required|numeric',
        ]);
        // if ($validator->fails()) return new Respons(false, 'Validation Failed', $validator->errors());
        if ($validator->fails()) return response()->badRequest('Validarion Failed',$validator->errors());


        // $otp = Otpcode::where(['key' => $req->email, 'status'=>1])->first();
        // if(!$otp) return new Respons(false, 'Email belum diverifikasi');


        $input  = $only;
        $input['password'] = bcrypt($input['password']);
        $input['time'] = $this->strleft;
        return $this->repo->register($input);
    }

    public function registerGoogle(Request $req)
    {
        $only = $req->only('name', 'email', 'photo');
        $validator = Validator::make($only, [
            'name' => 'required',
            'email' => 'required|email:filter,spoof,dns|unique:users,email',
        ]);
        if ($validator->fails()) return new Respons(false, 'Validation Failed', $validator->errors());
        $only['time'] = $this->strleft;

        return $this->repo->registerWithGoogle($only);

    }


    public function handleLoginGoogle()
    {
        try {
            DB::beginTransaction();

            $user = Socialite::driver('google')->stateless()->user();
            $userMain = User::firstOrCreate([
                'email' => $user->email,
                'name' => $user->name,
                'photo' => $user->avatar,
                'password' => 0
            ]);

            $userMarketId = str_pad($userMain->id, 11, $this->strleft, STR_PAD_RIGHT); //BUAT RANDOM USER ID

            UserMarket::create([
                'user_id_main' => $userMain->id,
                'user_id_market' => $userMarketId,
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return new Respons(false, $th->errorInfo[2], $th);
        }

        $res = new stdClass;
        $res->name = $userMain->name;
        $res->email = $userMain->email;
        $res->token = $userMain->createToken('token-name')->plainTextToken;
        // return response()->json($res, 200);
        return new Respons(true, 'Register Succesfully', $res);
    }
}
