<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use DB;
use Redirect;
use Input;
use Mail;
use App\Helper;
use Illuminate\Support\Facades\Validator;
use App\User;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('web')->except('logout');
    }

    public function showLogin()
    {   
        return view('auth.login');
    }

    public function doLogin(Request $request)
    {
        // validate the info, create rules for the inputs
        $rules = array(
            'email'    => 'required|string|email|max:255',
            'password' => 'required|min:6' // password can only be alphanumeric and has to be greater than 6 characters
        );
        
         // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);
        // if the validator fails, redirect back to the form
        
        $request->request->add(['user_type' => ['2','3','4']]); //add request

        $email = $request->email;

        if ($validator->fails()) {
            $email = $request->email.'@mobile.com';
            $request->request->add(['email' => $email]); //add request
            //return json_encode(['success' =>0, 'msg' => trans('word.The email must be a valid email address'),'result' =>[] ]);
        }
        /*if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator) // send back all errors to the login form
                ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        }
        else 
        {*/
        
            // create our user data for the authentication
            $userdata = array(
                'email'     => $email,
                'password'  => Input::get('password'),
                'status'  => '1',
            );
            
            $user = DB::table('users')->where('email','=', $email)->orderBy('id','desc')->first();
            
          if($user != null && $user->approve == '1' && $user->status == '1' && $user->is_verify == "1")
          {
              if($user->user_type == "2") // shipper
              {
                  if (Auth::guard('shipper')->attempt($userdata)) {

                    // update user ip in udid
                    DB::table('users')->where('id', $user->id)->update(['udid' => $request->ip(),'device_type' => '0','device_token' => null]);

                    return Redirect::to('shipper/dashboard');
                      
                  } else {     

                      // validation not successful, send back to form
                      session()->flash('alert-danger', 'Invalid Login Credential or Account Not Approved Yet.');
                      return redirect()->route('showLogin');
                  }
              
              }else if($user->user_type == "3") // transporter
              { 
                  if (Auth::guard('transporter')->attempt($userdata)) {

                    // update user ip in udid
                    DB::table('users')->where('id', $user->id)->update(['udid' => $request->ip(),'device_type' => '0','device_token' => null]);

                    return Redirect::to('transporter/dashboard');
                      
                  } else {     

                      // validation not successful, send back to form 
                      session()->flash('alert-danger', 'Invalid Login Credential or Account Not Approved Yet.');
                      return redirect()->route('showLogin');
                  }
              
              }else if($user->user_type == "4") // driver
              {
                  if (Auth::guard('driver')->attempt($userdata)) {

                    // update user ip in udid
                    DB::table('users')->where('id', $user->id)->update(['udid' => $request->ip(),'device_type' => '0','device_token' => null]);

                    return Redirect::to('driver/dashboard');
                      
                  } else {     
                    
                      // validation not successful, send back to form 
                      session()->flash('alert-danger', 'Invalid Login Credential or Account Not Approved Yet.');
                      return redirect()->route('showLogin');
                  }
              }else{

                    // validation not successful, send back to form 
                      session()->flash('alert-danger', 'Invalid Login Credential');
                      return redirect()->route('showLogin');
              }
         
          }else if($user != null && $user->approve == '2'){

            // validation not successful, send back to form 
                    session()->flash('alert-danger', 'This Account Rejected By Admin.');
                    return redirect()->route('showLogin');

          }else if($user != null && $user->is_verify == '0'){

            // validation not successful, send back to form 
                    session()->flash('alert-danger', 'Please Verify your email first.');
                    return redirect()->route('showVerifyUser',$user->id);

          }else if($user != null && $user->approve == '0'){

            // validation not successful, send back to form 
                    session()->flash('alert-danger', 'Account Pending for Admin Approval.');
                    return redirect()->route('showLogin');
          
          }else if($user != null && $user->status == '2'){

            // validation not successful, send back to form 
                    session()->flash('alert-danger', 'Account Deactivated By Admin.');
                    return redirect()->route('showLogin');
          
          }else{
                   
                 // validation not successful, send back to form 
                    session()->flash('alert-danger', 'User Not Found.');
                    return redirect()->route('showLogin');
          }

        /*}*/

    }

    public function doDriverLogout()
    {
      Auth::guard('driver')->logout(); // log the user out of our application
      return Redirect::to(route('showLogin')); // redirect the user to the login screen
    }
    
    public function doTransporterLogout()
      {
        Auth::guard('transporter')->logout(); // log the user out of our application
        return Redirect::to(route('showLogin')); // redirect the user to the login screen
      }
      
    public function doShipperLogout()
      {
        Auth::guard('shipper')->logout(); // log the user out of our application
        return Redirect::to(route('showLogin')); // redirect the user to the login screen
      }
      

    public function show_forgot_password()
    {
        return view('forgot_password');
    }

    public function forgot_password(Request $req)
    {
            
        $forgot = DB::table('users')->where('email','=',$req->email)->whereIn('user_type', ["2","3","4"])->first();
                
             if($forgot)
             {
                 $forgot_token = Str::random(8);
                 $link = url('/').'/reset_password/'.$forgot->id.'/'.$forgot_token;
                 
                 $fo=User::find($forgot->id);
                 $fo->forgot_token=$forgot_token;
                 $fo->save();
                 $data = array();
                 $data['link'] = $link;
                 $data['first_name'] = $fo->first_name;
                 if($fo->user_type != '1'){

                    $data['email'] = $fo->email;
                 }


                  Mail::send('emails.reset_email', ['user' => (object)$data], function($message) use ($data) {
                            $message->from(env('MAIL_USERNAME'), 'KMIOU');
                            $message->to($data['email']);
                            $message->subject('KMIOU Reset Password');
                        });

                session()->flash('alert-success', 'Reset password link sent in your mail');
                return redirect()->route('showLogin');
            }
            else{
                 
                return back()->with('alert-warning','email not found');
            }
    }

    public function dashboard()
    {
        return view('welcome');

    }
}
