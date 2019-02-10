<?php

namespace App\Http\Controllers;

use App\Admin;
use App\User;
use App\Custom;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesUsers;
    
    public $redirectPath = '/';
    public $redirectAfterLogout = '/login';
    public $loginPath = 'login'; 

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);		    
    }

    public function getLogin()
    {                
        return view('before_login.login');
    }
    
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {        
        $status = 0;
        $msg = "The credential that you've entered doesn't match any account.";
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email', 
            'password' => 'required',            
        ]);        
        
        // check validations
        if ($validator->fails()) 
        {
            $messages = $validator->messages();
            
            $status = 0;
            $msg = "";
            
            foreach ($messages->all() as $message) 
            {
                $msg .= $message . "<br />";
            }
        }         
        else
        {
            if(Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) 
            {
                $status = 1;
                $msg = "Logged in successfully.";
            }
        }
        
        // $url = redirect()->intended($this->redirectPath);
        if($request->isXmlHttpRequest())
        {
            return ['status' => $status, 'msg' => $msg];
        }
        else
        {
            if($status == 0)
            {
                session()->flash('error_message', $msg);
            }
            
            return redirect('login');
        }        
    }    
    
    public function getLogout()
    {		
	   // logout user
       Auth::logout();
       return redirect($this->redirectAfterLogout);
    }  

    public function forgotPassword()
    {
        return view('before_login.forgotPassword');
    }

    public function forgotPasswordData(Request $request)
    {
        $status = 1;
        $msg = "We have sent you an email so that you can restore your password. Please check your mail.";

        $validator = Validator::make($request->all(), [
            'email' => 'required|email', 
        ]);        
        
        // check validations
        if ($validator->fails()) 
        {
            $messages = $validator->messages();
            
            $status = 0;
            $msg = "";
            
            foreach ($messages->all() as $message) 
            {
                $msg .= $message . "<br />";
            }
        }else{
            $email = $request->get('email');
            $user = User::where('email',$email)->first();
            
            if(!$user){
                $status = 0;
                $msg = "Email is not correct. Enter one correctly";
            }else{

                $key = \App\Custom::generatePassword(12);
                $user->reset_password_token = $key;
                $user->save();

                // send email to user
                $email = trim($user->email);
                $subject = 'Forgotten password';
                $path = route('resetPassword',['id'=>$user->id,'key'=>$key]);
 
                $message = array();
                $message['name'] = ucwords($user->name);
                $message['path'] = $path;

                $returnHTML = view('emails.forgotPassword',$message)->render();
                $params["to"]=$email;
                $params["subject"] = $subject;
                $params["body"] = $returnHTML;
                Custom::sendHtmlMail($params);
            }
        }
        return ['status' => $status, 'msg' => $msg];
    }
    public function resetPassword($id,$key)
    {
        $user = User::where('id',$id)->where('reset_password_token',$key)->first();
        if(!$user)
        {
            return redirect('/');
        }
        else
        {
            $data = array();
            $data['key'] = $id.'/'.$key;
            return view('before_login.resetPassword',$data);
        }

    }
    public function resetPasswordData(Request $request)
    {
        $status = 1;
        $msg = 'Password has been updated successfully!';

        $keys = $request->get('key');
        $keys = explode('/', $keys);
        $id = isset($keys[0])?$keys[0]:'';
        $key = isset($keys[1])?$keys[1]:'';
        
        $user = User::where('id',$id)->where('reset_password_token',$key)->first();
        if(!$user){
            $status = 0;
            $msg = 'User not found';
        }

        // check validations
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6|confirmed',            
            'password_confirmation' => 'required',
        ]);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            
            $status = 0;
            $msg = "";
            
            foreach ($messages->all() as $message) 
            {
                $msg .= $message . "<br />";
            }
        }else{
            
            $password = $request->get('password');

            $user->password = bcrypt($password);
            $user->reset_password_token = NULL;
            $user->save();
        }
        return ['status' => $status, 'msg' => $msg];
    }
}
