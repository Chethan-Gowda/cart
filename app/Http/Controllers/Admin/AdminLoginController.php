<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\Websitemail;
use App\Models\Admin;
use Hash;
use Auth;

class AdminLoginController extends Controller
{
    public function login()
    {
       /*  $pass= Hash::make('admin123');
        dd($pass); */
        return view('admin.login');
    }
    public function forgetPassword()
    {
        return view('admin.forget_password');
    }
    public function loginSubmit(Request $request)
    {      
       $request->validate([
        'email' => 'required|email',
        'password' => 'required'
       ]);

       $credential = [
        'email'=> $request->email,
        'password'=> $request->password,
       ];

       if(Auth::guard('admin')->attempt($credential))
       {
         return redirect()->route('admin_home');
       }
       else{
        return redirect()->route('admin_login')->with('error','Please enter valid credentials');
       }
    }


    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin_login')->with('error','logout Successfull');
    }


    public function forgetPasswordSubmit(Request $request)
    {
            $request->validate([
                'email' => 'required|email'           
            ]);


           $adminData = Admin::where('email',$request->email)->first();
           if(!$adminData){
            return redirect()->back()->with('error','Email not found!. ');
           }

           $token = hash('sha256', time());

           /* update the admin token field */
           $adminData->token = $token;
           $adminData->update();

           $resetUrl = url('admin/reset-password/'.$token.'/'.$request->email);
           $subject = 'Reset Link';
           $message = 'Please click on the following link to reset the password';
           $message.='<a href="'. $resetUrl.'">Click Here</a>';

           \Mail::to($request->email)->send(new Websitemail($subject, $message));

           return redirect()->route('admin_login')->with('success','Please check your mail for reset link');


    }



    public function resetPassword($token, $email){
         //echo $token;
         $adminData  = Admin::where('token',$token)->where('email',$email)->first();
         if(!$adminData){
            return redirect()->route('admin_login');
         }
         return view('admin.reset_password', compact('email','token'));
    }


    public function resetPasswordSubmit(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'confirmPassword' => 'required|same:password'
           ]);
           
          /* generate hash password */
          $password = $request->password;
          $token = $request->token;
          $email = $request->email;
          $updatedPassword  = Hash::make($password);

          $adminData  = Admin::where('token',$token)->where('email',$email)->first();

          if(!$adminData){
            return redirect()->route('admin_login')->with('erroe','Please try again');
          }

          $adminData->password = $updatedPassword;
          $adminData->token ='';
          $adminData->update();

          $subject ="Password Reset Successfull";
          $message ="Dear Admin, Your password is reset successfull";

          \Mail::to($request->email)->send(new Websitemail($subject, $message));

          return redirect()->route('admin_login')->with('success','Please check your mail for reset link');
      }



}
