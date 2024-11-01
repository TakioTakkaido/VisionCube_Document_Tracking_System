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
use App\Models\Log as ModelsLog;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller {
    // Show Account
    public function show(Request $request){
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
            ModelsLog::create([
                'account' => Auth::user()->name . " • " . Auth::user()->role,
                'description' => 'Logged in to the system'
            ]);
            
            // Authentication passed, to dashboard
            return response()->json([
                'success' => 'Login successful.'
            ]);
        }
        
        // Authentication failed, redirect back with an error message
        return response()->json([
            'errors' => [
                'password' => 'Password does not match or exist for the account.'
            ]
        ], 422);
    }

    //Create New Account
    public function create(CreateAccountFormRequest $request){
        // Validate credentials
        $request->validated();
        
        // Make new account
        Account::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role')
        ]);

        // Create new log file
        ModelsLog::create([
            'account' => Auth::user()->name . " • " . Auth::user()->role,
            'description' => 'Added new user to the system'
        ]);

        return response()->json([
            'success' => 'Account created successfully'
        ]);

    }

    // Forgot Password
    public function forgotPassword(ForgotPasswordRequest $request){
        // Validate credentials
        $request->validated();
        
        // Add additional instuction like sending a verification link
        // that when clicked would redirect to a link that would change the password
        // and redirect the user to the log in page to input the new password

        // Create new log
        ModelsLog::create([
            'account' => Auth::user()->name . " • " . Auth::user()->role,
            'description' => 'Requested to change password'
        ]);

        // Temporary fix: redirect to login for now
        return redirect()->route('show.login');
    }

    // Reset Password
    public function resetPassword(){
        // 

        // Create new log
        ModelsLog::create([
            'account' => Auth::user()->name . " • " . Auth::user()->role,
            'description' => 'Resetted password'
        ]);

    }

    // Log In Admin User
    public function loginAdmin(Request $request){
         // Validate the login credentials
         $credentials = $request->only('email', 'password');
         $remember = $request->has('remember');
 
         if (Auth::guard('web')->attempt($credentials, $remember)) {

            // Create new log
            ModelsLog::create([
                'account' => Auth::user()->name . " • " . Auth::user()->role,
                'description' => 'Logged in to the system'
            ]);

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
        // Create new log
        ModelsLog::create([
            'account' => Auth::user()->name . " • " . Auth::user()->role,
            'description' => 'Logged out to the system'
        ]);

        // Logout
        Auth::logout();

        return response()->json(['redirect' => route('show.login')]);
    }

    // Deactivate
    public function deactivate(Request $request){
        // Find the account
        $account = Account::find($request->id);

        $account->deactivated = true;

        $account->save();

        // Create new log
        ModelsLog::create([
            'account' => Auth::user()->name . " • " . Auth::user()->role,
            'description' => 'Account deactivated'
        ]);
    }

    //ADMIN FUNCTIONS
    public function showAllActiveAccounts(){
        // Obtain all active accounts
        $accounts = Account::where('deactivated', 0)->get();

        // Log
        Log::channel('daily')->info('All accounts obtained: {accounts}', ['accounts' => $accounts]);

        // Return all accounts
        return response()->json([
            'accounts' => $accounts
        ]);
    }

    public function showAllDeactivatedAccounts(){
        // Get all deactivated accounts
        $accounts = Account::where('deactivated', 1)->get();

        // Log
        Log::channel('daily')->info('Deactivated accounts obtained: {accounts}', ['accounts' => $accounts]);

        return response()->json([
            'success' => 'Successfully obtained deactivated accounts',
            'accounts' => $accounts
        ]);
    }

    public function editAccountRole(Request $request){
        // Find account
        $account = Account::find($request->id);

        // Change the role
        $account->role = $request->role;

        // Save new account role
        $account->save();
        
        // Create new log
        ModelsLog::create([
            'account' => Auth::user()->name . " • " . Auth::user()->role,
            'description' => 'Edited account role'
        ]);
    }

    public function reactivate(Request $request){
        /// Find the account
        $account = Account::find($request->id);

        $account->deactivated = false;

        $account->save();

        // Create new log
        ModelsLog::create([
            'account' => Auth::user()->name . " • " . Auth::user()->role,
            'description' => 'Reactivated account'
        ]);
    }

    public function editAccess(Request $request){
        // Find all accounts of that role
        $secretaries = Account::where('role', "Secretary")->get();
        $assistants = Account::where('role', "Assistant")->get();
        $clerks = Account::where('role', "Clerk")->get();

        // Edit access for each roles
        foreach ($secretaries as $secretary) {
            $secretary->canUpload     = filter_var($request->secretaryAccesses[0], FILTER_VALIDATE_BOOLEAN);
            $secretary->canEdit       = filter_var($request->secretaryAccesses[1], FILTER_VALIDATE_BOOLEAN);
            $secretary->canMove       = filter_var($request->secretaryAccesses[2], FILTER_VALIDATE_BOOLEAN);
            $secretary->canArchive    = filter_var($request->secretaryAccesses[3], FILTER_VALIDATE_BOOLEAN);
            $secretary->canDownload   = filter_var($request->secretaryAccesses[4], FILTER_VALIDATE_BOOLEAN);
            $secretary->canPrint      = filter_var($request->secretaryAccesses[5], FILTER_VALIDATE_BOOLEAN);
            dd($secretary);
            $secretary->save();
        }   

        foreach ($assistants as $assistant) {
            $assistant->canUpload     = filter_var($request->assistantAccesses[0], FILTER_VALIDATE_BOOLEAN);
            $assistant->canEdit       = filter_var($request->assistantAccesses[1], FILTER_VALIDATE_BOOLEAN);
            $assistant->canMove       = filter_var($request->assistantAccesses[2], FILTER_VALIDATE_BOOLEAN);
            $assistant->canArchive    = filter_var($request->assistantAccesses[3], FILTER_VALIDATE_BOOLEAN);
            $assistant->canDownload   = filter_var($request->assistantAccesses[4], FILTER_VALIDATE_BOOLEAN);
            $assistant->canPrint      = filter_var($request->assistantAccesses[5], FILTER_VALIDATE_BOOLEAN);
            
            $assistant->save();

        }   

        foreach ($clerks as $clerk) {
            $clerk->canUpload     = filter_var($request->clerkAccesses[0], FILTER_VALIDATE_BOOLEAN);
            $clerk->canEdit       = filter_var($request->clerkAccesses[1], FILTER_VALIDATE_BOOLEAN);
            $clerk->canMove       = filter_var($request->clerkAccesses[2], FILTER_VALIDATE_BOOLEAN);
            $clerk->canArchive    = filter_var($request->clerkAccesses[3], FILTER_VALIDATE_BOOLEAN);
            $clerk->canDownload   = filter_var($request->clerkAccesses[4], FILTER_VALIDATE_BOOLEAN);
            $clerk->canPrint      = filter_var($request->clerkAccesses[5], FILTER_VALIDATE_BOOLEAN);

            $clerk->save();
        }   

        // Create new log
        ModelsLog::create([
            'account' => Auth::user()->name . " • " . Auth::user()->role,
            'description' => 'Edited access of roles'
        ]);

        return response()->json([
            'success' => 'Edited roles successfully'
        ]);
    }

    public static function getSecretaryAccesses(){
        // Get first instance of that role
        $secretary = Account::where('role', 'Secretary')->first();
        $access = [];

        // Get all access
        $access[0] = isset($secretary->canUpload) ? $secretary->canUpload : false;
        $access[1] = isset($secretary->canEdit) ? $secretary->canEdit : false;
        $access[2] = isset($secretary->canMove) ? $secretary->canMove : false;
        $access[3] = isset($secretary->canArchive) ? $secretary->canArchive : false;
        $access[4] = isset($secretary->canDownload) ? $secretary->canDownload : false;
        $access[5] = isset($secretary->canPrint) ? $secretary->canPrint : false;

        // Return all access
        return $access;
    }

    public static function getAssistantAccesses(){
        // Get first instance of that role
        $assistant = Account::where('role', 'Assistant')->first();
        $access = [];

        // Get all access
        $access[0] = isset($assistant->canUpload) ? $assistant->canUpload : false;
        $access[1] = isset($assistant->canEdit) ? $assistant->canEdit : false;
        $access[2] = isset($assistant->canMove) ? $assistant->canMove : false;
        $access[3] = isset($assistant->canArchive) ? $assistant->canArchive : false;
        $access[4] = isset($assistant->canDownload) ? $assistant->canDownload : false;
        $access[5] = isset($assistant->canPrint) ? $assistant->canPrint : false;
        
        // Return all access
        return $access;
    }

    public static function getClerkAccesses(){
        // Get first instance of that role
        $clerk = Account::where('role', 'Clerk')->first();
        $access = [];

        // Get all access
        $access[0] = isset($clerk->canUpload) ? $clerk->canUpload : false;
        $access[1] = isset($clerk->canEdit) ? $clerk->canEdit : false;
        $access[2] = isset($clerk->canMove) ? $clerk->canMove : false;
        $access[3] = isset($clerk->canArchive) ? $clerk->canArchive : false;
        $access[4] = isset($clerk->canDownload) ? $clerk->canDownload : false;
        $access[5] = isset($clerk->canPrint) ? $clerk->canPrint : false;
        
        // Return all access
        return $access;


    }
}
