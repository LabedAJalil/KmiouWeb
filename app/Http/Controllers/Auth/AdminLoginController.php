<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Auth;
use DB;
use Redirect;
use Input;
use Mail;
use Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;

class AdminLoginController extends Controller
{
	protected $redirectTo = '/admin/dashboard';

	use AuthenticatesUsers;

    protected function guard()
    {
      return Auth::guard('admin');
    }

    public function showAdminLogin()
	{
	    return view('admin/login');
	}

	public function doAdminLogin()
	{
		// validate the info, create rules for the inputs
		$rules = array(
		    'email'    => 'required|email', // make sure the email is an actual email
		    'password' => 'required|min:3' // password can only be alphanumeric and has to be greater than 3 characters
		);
		// run the validation rules on the inputs from the form
		$validator = Validator::make(Input::all(), $rules);
		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
		    return Redirect::to('adminLogin')
		        ->withErrors($validator) // send back all errors to the login form
		        ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
		}
		else 
		{

			 // create our user data for the authentication
            $userdata = array(
                'email'     => Input::get('email'),
                'password'  => Input::get('password'),
            );
              
	         $user = DB::table('users')->where('email','=', Input::get('email'))->first();

          if($user)
          {
            
              if($user->user_type == "1"){ //super admin
                
                  // attempt to do the login
                  if (Auth::guard('admin')->attempt($userdata)) {

                      return redirect()->route('adminDashboard');
                      
                  } else {     

                    session()->flash('alert-danger', 'Invalid Login Credentials.');
                    return redirect()->route('adminLogin');
                  }

              }else{

                    session()->flash('alert-danger', 'Invalid Login Credentials.');
                    return redirect()->route('adminLogin');
              }
            }

		}

	}
	public function doAdminLogout()
	{
		Auth::guard('admin')->logout(); // log the user out of our application
		return Redirect::to(route('adminLogin')); // redirect the user to the login screen
	}
	

	/*change password*/
    public function show_change_password(Request $request)
    {   
        $user = User::find($request->user_id);

        return view('admin/change_password',compact('user')); 
    }

    public function change_password(Request $req)
    {
        $change = DB::table('users')->where('id','=',$req->user_id)->first();

         if($change)
        {

            if(Hash::check($req->old_password,$change->password))
            {
                
                $user = DB::table('users')->where('email','=',$req->email)->first();

                $New_pass = $req->new_password;
                $Con_pass = $req->confirm_password;
                if($New_pass == $Con_pass)
                {
                      $change_password = User::find($req->user_id);
                     
                      $change_password->password=Hash::make($req->new_password); 
                      $change_password->save();

                    session()->flash('alert-success', 'password changed successfully');
                    return redirect()->route('adminLogin');
                }
                else
                {
                    return back()->with('alert-danger','New Password & Confirmed Password Not Matched !!!');
                }
            }
            else{
                return back()->with('alert-warning','old password is wrong');
             }

        }else{
            return back()->with('alert-warning','user not found');
        }

    }
    
    /*change password*/
}
