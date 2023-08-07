<?php

namespace App\Http\Controllers\Api\Auth;

use stdClass;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\Respons;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Marketplace\UserMarket;
use Laravel\Socialite\Facades\Socialite;
use App\Mail\OTP\Register as OTPRegister;
use App\Models\Otp\Otpcode;
use Illuminate\Support\Facades\Validator;

class Register extends Controller
{
    protected $strleft;

    public function __construct()
    {
        $this->strleft = substr(Carbon::now()->timestamp, 3);
    }

    public function registerSendOtp(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email' => 'required|email:filter,spoof,dns|unique:users,email',
        ]);

        if ($validator->fails()) {
            return new Respons(false, 'Validation Failed', $validator->errors());
        }

        // GET OTP
        $validUntil = Carbon::now()->addMinute(5);
        $codeOTP = rand(1000, 9999);
        Otpcode::updateOrCreate(
            ['key' => $req->email],
            ['otp' => $codeOTP, 'tipe' => 'REGISTER', 'valid' => $validUntil, 'status'=>0],
        );

        $mailData = [
            'codeOTP' => $codeOTP,
            'title' => 'REGISTER CODE'
        ];
        Mail::to($req->email)->send(new OTPRegister($mailData));
        return new Respons(true, 'Code Sent Succesfully', $mailData);
    }

    public function registerConfirmOtp(Request $req)
    {

        $otp = Otpcode::where(['key' => $req->key, 'otp' => $req->otp, 'status'=>0])->first();
        if (!$otp) return new Respons(false, 'OTP Does Not Match');

        if (Carbon::now() > $otp->valid) return new Respons(false, 'OTP Timeout');

        $otp->status = 1;
        $otp->save();
        return new Respons(true, 'OTP Confirmed');
    }

    public function register(Request $req)
    {
        // Validasi
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'email' => 'required|email:filter,spoof,dns|unique:users,email',
            'password' => 'required',
            'phone' => 'required',
            'otp' => 'required',
        ]);
        if ($validator->fails()) return new Respons(false, 'Validation Failed', $validator->errors());


        $otp = Otpcode::where(['key' => $req->email, 'otp' => $req->otp, 'status'=>1])->first();
        if(!$otp) return new Respons(false, 'OTP Does Not Match');


        $input  = $req->all();
        $input['password'] = bcrypt($input['password']);
        try {
            // input ke db
            DB::beginTransaction();
            $userMain = User::create($input);
            $userMarketId = str_pad($userMain->id, 11, $this->strleft, STR_PAD_RIGHT); //BUAT RANDOM USER ID
            UserMarket::create([
                'user_id_main' => $userMain->id,
                'user_id_market' => $userMarketId,
            ]);
            $otp->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return new Respons(false, $th->errorInfo[2], $th);
        }

        $res = new stdClass;
        $res->token = $userMain->createToken($userMain->name)->plainTextToken;
        $res->name =  $userMain->name;
        return new Respons(true, 'Register Succesfully', $res);
    }

    public function registerGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
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
