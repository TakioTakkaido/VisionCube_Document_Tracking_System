<?php

namespace App\Http\Controllers;

// VISION CUBE SOFTWARE CO. 
// Controller: DocumentController
// Facilitates the functionalities to access modal data Account
// made by the requests from the view
// 
// It contains:
// -Storing and creation of account
// -Validation of the login information inputted by a user
// -Validation of the login information inputted by an admin user
// -Sending of verification link whenever the password is forgotten
// Contributor/s: 
// Calulut, Joshua Miguel C.

// Enums
use App\AccountRole;

use App\Models\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAccountFormRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginFormRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller {
    // Show Account
    public function show(Request $request){
        // 
    }

    // Login Existing User
    public function login(LoginFormRequest $request){
        // Validate credentials
        $request->validated();

        // Retrieve the necessary credentials
        $credentials = $request->safe()->only('email', 'password');
        $remember = $request->safe()->has('remember');

        // Authorization attempt
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Send log

            
            // Authentication passed, to dashboard
            return response()->json([
                'success' => 'Login successful.'
            ]);
        }
        
        // Authentication failed, redirect back with an error message
        return response()->json([
            'errors' => [
                'email' => 'Email does not match the given records.',
                'password' => 'Incorrect password inputted.'
            ]
        ], 422);
    }

    //Create New Account
    public function create(CreateAccountFormRequest $request){
        // Validate credentials
        $request->validated();
        
        // Make new guest account
        $guest = Account::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => AccountRole::GUEST
        ]);

        Auth::login($guest);
        
        // Emit CreatedNewAccount event

        // To dashboard
        return redirect()->route('account.dashboard');
    }

    // Forgot Password
    public function forgotPassword(ForgotPasswordRequest $request){
        // Validate credentials
        $request->validated();
        
        // Add additional instuction like sending a verification link
        // that when clicked would redirect to a link that would change the password
        // and redirect the user to the log in page to input the new password

        // Temporary fix: redirect to login for now
        return redirect()->route('show.login');
    }

    // Reset Password
    public function resetPassword(){
        // 
    }

    // Log In Admin User
    public function loginAdmin(Request $request){
         // Validate the login credentials
         $credentials = $request->only('email', 'password');
         $remember = $request->has('remember');
 
         if (Auth::guard('web')->attempt($credentials, $remember)) {
             // Authentication passed, redirect to dashboard
             return redirect()->intended(route('show.dashboard'))->with([
                 'success' => 'Login Successful'
             ]);
         }
         
         // Authentication failed, redirect back with an error message
         return redirect()->route('account.showLogInAdmin')->withErrors([
             'email' => 'The provided credentials do not match our records.',
         ]);
    }

    // Logout
    public function logout(){
        Auth::logout();
        return redirect()->route('account.showLogIn');
    }

    // Deactivate
    public function deactivate(){
        // 
    }

    //ADMIN FUNCTIONS

    public function showAllAccounts(){
        // 
    }

    public function showAllDeactivatedAccounts(){
        $accounts = Account::where('deactivated', false)->get();

        return response()->json([
            'success' => 'All deactivated accounts obtained.',
            'accounts' => $accounts
        ]);
    }

    public function editAccountRole(Request $request){
        // 
    }

    public function verifyAccount(Request $request){
        // 
    }

    public function rejectGuest(Request $request){
        // 
    }

    public function reactivate(Request $request){
        // 
    }
}
