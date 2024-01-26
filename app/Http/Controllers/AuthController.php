<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use Hash;


class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function Register_store(Request $request)
{  
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
    ]);
       
    $data = $request->all();
    
    // Assuming you want to use the create method from the User model
    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
    ]);

    return redirect("dashboard")->withSuccess('Great! You have Successfully logged in');
}

    /**
     * Display the specified resource.
     */
    public function dashboard()
    {
        if(Auth::check()){
            return view('dashboard');
        }
  
        return redirect("login")->withSuccess('Opps! You do not have access');
    }


    public function Login_store(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
    
        $user = $request->only('email', 'password');
        if (Auth::attempt($user)) {
            return redirect()->intended('dashboard')
                ->withSuccess('You have Successfully logged in');
        }
        return redirect("login")->withErrors(['error' => 'Invalid email or password']);
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function logout() {
        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
