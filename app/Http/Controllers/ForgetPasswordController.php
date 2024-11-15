<?php

namespace App\Http\Controllers;

use App\Mail\SendOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgetPasswordController extends Controller
{
    public function index()
    {
        return view('auth.forgot_password.index');
    }


    public function mail(Request $request)
    {
        //$otp = rand(0000,9999);
        $otp = '1111';

        $user = User::whereEmail($request->email)->first();

        if($user == null) {
            return redirect()->back()->with(['message' => 'البريد الإلكترونى للمستخدم غير مسجل', 'alert' => 'alert-danger']);
        }

       $user->otp = $otp;
       $user->update();

        $details = [
            'title' => 'إعادة إدخال كلمة المرور',
            'body' => 'رقم ال OTP هو'.$otp
        ];

        //Mail::to($request->email)->send(new SendOtp($details));

        return view('auth.forgot_password.otp')->withEmail($request->email);
    }

    public function checkOtp(Request $request)
    {
        $user = User::whereEmail($request->email)->first();

        if($request->otp == $user->otp){
            return view('auth.forgot_password.reset_password')->withEmail($request->email);
        }else {
            return view('auth.forgot_password.otp')->with(['message' => 'رقم ال otp غير صحيح', 'alert' => 'alert-danger'])->withEmail($request->email);
        }
    }

    public function resetPassword(Request $request)
    {
        $user = User::whereEmail($request->email)->first();

        /*if($request->password !== $request->password_confirmation){
            return view('auth.forgot_password.reset_password')->with(['message' => 'كلمة المرور غير متطابقة', 'alert' => 'alert-danger'])->withEmail($request->email);
        }*/

        $validator = Validator::make($request->all(), ['password' => 'required|min:8|max:25|confirmed']);

        if($validator->fails()){
            return view('auth.forgot_password.reset_password')->withErrors($validator)->withEmail($request->email);
        }

        $user->password = bcrypt($request->password);

        $user->update();

        return redirect()->route('login');
    }
}
