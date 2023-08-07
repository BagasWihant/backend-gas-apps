<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Models\Otp\Otpcode;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Resources\Respons;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Marketplace\UserMarket;
use App\Mail\OTP\Register as OTPRegister;
use Illuminate\Support\Facades\Validator;

class Login extends Controller
{
    public function login(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'email'  => 'required|email',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return new Respons(false, 'Validation Failed', $validator->errors());
        }


        if (!Auth::attempt($req->only(['email','password']))) {
            return new Respons(false, 'Email / password salah', ['fail'=>true]);
        }

        // dd($req);
        $user = User::where('email', $req->email)->first();
        // if ($user->password == bcrypt($req->password)) {
        //     return new Respons(false, 'Password kami masukkan salah', ['fail'=>true]);
        // }
        $user->tokens()->delete();

        $success['token'] =  $user->createToken($user->name)->plainTextToken;
        return new Respons(true, 'Success Login', $success);
    }

    public function resetPasswordSendOtp(Request $req){
        $validator = Validator::make($req->all(), [
            'email' => 'required|email:filter,spoof,dns',
        ]);

        if ($validator->fails()) {
            return new Respons(false, 'Validation Failed', $validator->errors());
        }

        // GET OTP
        $validUntil = Carbon::now()->addMinute(5);
        $codeOTP = rand(1000, 9999);
        Otpcode::updateOrCreate(
            ['key' => $req->email],
            ['otp' => $codeOTP, 'tipe' => 'RESETPASS', 'valid' => $validUntil, 'status'=>0],
        );

        $mailData = [
            'codeOTP' => $codeOTP,
            'title' => 'RESET PASSWORD'
        ];
        Mail::to($req->email)->send(new OTPRegister($mailData));
        return new Respons(true, 'Code Sent Succesfully', $mailData);
    }

    public function resetPasswordConfirmOtp(Request $req){
        $otp = Otpcode::where(['key' => $req->key, 'otp' => $req->otp, 'status' => 0])->first();
        if (!$otp) return new Respons(false, 'OTP Does Not Match');

        if (Carbon::now() > $otp->valid) return new Respons(false, 'OTP Timeout');

        $otp->status = 1;
        $otp->save();
        return new Respons(true, 'OTP Confirmed');
    }

    public function resetPassword(Request $req){
         // Validasi
         $validator = Validator::make($req->all(), [
            'password' => 'required',
            'c_password' => 'required|same:password',
            'otp' => 'required',
            'email' => 'required',
        ]);
        if ($validator->fails()) return new Respons(false, 'Validation Failed', $validator->errors());

        $user = User::where('email',$req->email)->first();
        if(!$user) return new Respons(false, 'Email tidak ditemukan');

        $otp = Otpcode::where(['key' => $req->email, 'otp' => $req->otp, 'status'=>1])->first();
        if(!$otp) return new Respons(false, 'OTP Does Not Match');

        $newPassword = bcrypt($req->password);
        try {
            // input ke db
            DB::beginTransaction();
            $user->password = $newPassword;
            $user->save();
            $otp->delete();
            DB::commit();
            return new Respons(true, 'Password reset successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return new Respons(false, $th->errorInfo[2], $th);
        }

    }
}
