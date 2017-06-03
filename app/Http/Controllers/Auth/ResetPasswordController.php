<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use DB;
use Hash;
use Session;
class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    public function reset(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            ]);
          if ($validation->fails()) {
                return Redirect::back()->withErrors($validation)->withInput();
         }
         $CheckUserRequest=DB::table('password_resets')->where('email',$request->email)->first();
         if ($CheckUserRequest &&  Hash::check($request->token, $CheckUserRequest->token)) {
    
              DB::table('users')->where('email',$request->email)->update(['password' =>  Hash::make($request->password)] );
              DB::table('password_resets')->where('email',$request->email)->delete();
              Session::flash('message', 'Your password is changed sucesfully ,Please login with yor new password.');
              Session::flash('status', 'success');
              return redirect('login'); 
         }

         Session::flash('message', 'There was something wrong with your request ,please tray again later ');
         Session::flash('status', 'error');
       
       return redirect()->back();

    }
   
}
