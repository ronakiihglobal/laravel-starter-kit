<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;




class AuthController extends Controller
{




    public function resetPassword(Request $request){
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                //event(new PasswordReset($user));
            }
        );

        if( $request->is('api/*')){
            if($status == Password::PASSWORD_RESET){
                return response()->json(["message" => "Password changed successfully"]);
            }else{
                return response()->json(["message" => "Something went wrong!"]);
            }
        }else{

            return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))->withSuccess('Password changed successfully!')
                    : back()->withErrors(['email' => [__($status)]]);    
        }
        
    }

    public function forgotPassword(Request $request) {

        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if( $request->is('api/*')){
        
            if($status == Password::RESET_LINK_SENT){
                return response()->json(["message" => "Password reset link sent successfully"]);
            }else{
                return response()->json(["message" => "Something went wrong!"]);
            }
        

        }else{

            return $status === Password::RESET_LINK_SENT
                        ? back()->with(['status' => __($status)])->withSuccess('Mail sent successfully!')
                        : back()->withErrors(['email' => __($status)]);
        }

    }

    public function loginForm(Request $request){

        return View("auth.login");

    }

    public function registrationForm(Request $request){

        return View("auth.registration");

    }

    public function dashboard(Request $request){

        return View("auth.dashboard",[ 'user' => Auth::user()->email ]);

    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            
            if( $request->is('api/*')){

            	$device_name = ($request->device_name) ? $request->device_name : config("app.name");
            	return Auth::user()->createToken($device_name)->plainTextToken;
            
            }else{

            	$request->session()->regenerate();
            	return redirect()->intended('/');
            }

        }else{
            throw ValidationException::withMessages(['The credentials are incorrect']);
        }

        //return response()->json(["email" => $request->email, "password" => $request->password], 422);
    }


    public function register(Request $request)
    {

        $request->validate([
            'email' => 'required|email|unique:App\Models\User,email',
            'password' => 'required|confirmed|min:6',
            'name' => 'required'
        ]);
        

        $user = new User();
        $user->password = Hash::make($request->password);
        $user->email = $request->email;
        $user->name = $request->name;
        $user->save();

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            
            if( $request->is('api/*')){

            	$device_name = ($request->device_name) ? $request->device_name : config("app.name");
            	return Auth::user()->createToken($device_name)->plainTextToken;
            
            }else{

            	$request->session()->regenerate();
            	return redirect()->intended('/');
            }
            
            
        }

        return response()->json(["email" => $request->email, "password" => $request->password], 422);
    }


    public function token(Request $request){

    	$request->validate([
	        'email' => 'required|email',
	        'password' => 'required',
	        'device_name' => 'required',
	    ]);

	    $user = User::where('email', $request->email)->first();

	    if (! $user || ! Hash::check($request->password, $user->password)) {
	        throw ValidationException::withMessages([
	            'email' => ['The provided credentials are incorrect.'],
	        ]);
	    }

	    $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return $user->createToken($request->device_name)->plainTextToken;
        }else{
            throw ValidationException::withMessages(['The credentials are incorrect']);
        }
	    

    }


    public function logout(Request $request){

        if( $request->is('api/*')){

        	Auth::user()->currentAccessToken()->delete();
    	
    	}else{
    	
    		$request->session()->regenerateToken();
        	$request->session()->invalidate();
    	       
            return redirect()->intended('/login');
    	}

        

        return response()->noContent();
    }
}