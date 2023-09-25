<?php

namespace Modules\V021\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Otp\Otpcode;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Resources\Respons;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Marketplace\UserMarket;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;

class LoginController extends Controller
{
    public function login(Request $req)
    {
        $validator = Validator::make($req->only('email','password'), [
            'email'  => 'required|email',
            'password' => 'required|min:6'
        ]);
        if ($validator->fails()) return response()->json(['message'=>'Validasi Gagal','data'=>$validator->errors()],400);




        if (!Auth::attempt($req->only(['email','password']))) {
            return response()->json(['message'=>'Email / Password Salah'],400);
        }

        $user = User::where('email', $req->email)->first();
        $userMarket = UserMarket::where('user_id_main',$user->id)->first();

        $token=  $user->createToken($user->name)->plainTextToken;
        $res = collect([
            'number' => $user->id,
            'market' =>$userMarket->user_id_market,
            'token' =>$token,
        ]);
        // return new Respons(true, 'Success Login', $res);
        return response()->json(['message'=>'Berhasil Login','data'=>$res]);
    }

    public function resetPasswordSendOtp(Request $req){
        $validator = Validator::make($req->only('email'), [
            'email' => 'required|email:filter,spoof,dns',
        ]);

        if ($validator->fails()) {
            // return new Respons(false, 'Validation Failed', $validator->errors());
            return response()->json(['message'=>'Validasi Gagal',$validator->errors()],400);
        }

        // GET OTP
        $validUntil = Carbon::now()->addSeconds(30);
        $codeOTP = rand(1000, 9999);
        Otpcode::updateOrCreate(
            ['key' => $req->email],
            ['otp' => $codeOTP, 'tipe' => 'RESETPASS', 'valid' => $validUntil, 'status'=>0],
        );

        $mailData = [
            'codeOTP' => $codeOTP,
            'title' => 'RESET PASSWORD'
        ];
        // Mail::to($req->email)->send(new Register($mailData));
        // return new Respons(true, 'Code Sent Succesfully', $mailData);
        return response()->json(['message'=>'Kode Berhasail Dikirim','data'=>$mailData]);
    }

    public function resetPasswordConfirmOtp(Request $req){
        $otp = Otpcode::where(['key' => $req->key, 'otp' => $req->otp, 'status' => 0])->first();
        if (!$otp) return response()->json(['message'=>'Otp Tidak Sama'],401);

        if (Carbon::now() > $otp->valid) return response()->json(['message'=>'Waktu Habis'],408);

        $otp->status = 1;
        $otp->save();
        return response()->json(['message'=>'Kode Berhasail Dikirim']);
    }

    public function resetPassword(Request $req){
         // Validasi
        $validator = Validator::make($req->only('email','password','c_password'), [
            'password' => 'required',
            'email' => 'required',
        ]);
        if ($validator->fails()) return response()->json(['message'=>'Validasi Gagal',$validator->errors()],400);

        $user = User::where('email',$req->email)->first();
        if(!$user) return response()->json(['message'=>'Email Tidak Ditemukan'],400);

        $otp = Otpcode::where(['key' => $req->email, 'status'=>1])->first();
        if(!$otp) return response()->json(['message'=>'OTP Tidak sama'],400);

        $newPassword = bcrypt($req->password);
        try {
            // input ke db
            DB::beginTransaction();
            $user->password = $newPassword;
            $user->save();
            $otp->delete();
            DB::commit();

            return response()->json(['message'=>'Password Berhasil direset']);
        } catch (\Throwable $th) {
            DB::rollBack();
            // return new Respons(false, $th->errorInfo[2], $th);
            return response()->json(['message'=>'Kesalahan Sistem'],400);
        }

    }
}
