<?php

namespace App\Http\Controllers;

use App\AccountRole;
use App\Models\Account;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:accounts',
            'password' => 'required|string|confirmed|min:8',
            'password_confirmation' => 'required'
        ]);
    
        Account::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => AccountRole::DEFAULT
        ]);
        
        return redirect()->route('account.dashboard')->with('success', 'User created successfully.');
    }

    // Log In User
    public function login(Request $request)
    {
        // Validate the login credentials
        $credentials = $request->only('email', 'password');
        
        if (Auth::guard('web')->attempt($credentials)) {
            // Authentication passed, redirect to dashboard
            return redirect()->intended(route('account.dashboard'))->with([
                'success' => 'Login Successful'
            ]);
        }
        
        // Authentication failed, redirect back with an error message
        return redirect()->route('account.showLogIn')->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // Log In Admin
    public function loginAdmin(Request $request){
         // Validate the login credentials
         $credentials = $request->only('email', 'password');
        
         if (Auth::guard('web')->attempt($credentials)) {
             // Authentication passed, redirect to dashboard
             return redirect()->intended(route('account.dashboard'))->with([
                 'success' => 'Login Admin Successful'
             ]);
         }
         
         // Authentication failed, redirect back with an error message
         return redirect()->route('account.showLogInAdmin')->withErrors([
             'email' => 'The provided credentials do not match our records.',
         ]);
    }

    // Forgot Password
    public function forgotPassword(Request $request){
        $request->validate([
            'email' => 'required|string|email|max:255|exists:accounts'
        ]);
        // Add additional instuction like sending a verification link
        // that when clicked would redirect to a link that would change the password
        // and redirect the user to the log in page to input the new password

        // Temporary fix: redirect to login for now
        return redirect()->route('account.showLogIn');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
