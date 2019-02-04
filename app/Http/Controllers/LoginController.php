<?php

namespace App\Http\Controllers;

use App\Admin;
use App\User;
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
}
