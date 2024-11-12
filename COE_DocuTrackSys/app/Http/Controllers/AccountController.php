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
use App\Mail\ResetPassword;
use App\Mail\VerifyEmail;
use App\Models\EmailVerificationToken;
use App\Models\Log as ModelsLog;
use App\Models\ResetPasswordToken;
use App\Models\Settings;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AccountController extends Controller {
    // Show Account
    public function show(Request $request){
        $account = Account::find($request->id);

        return response()->json([
            'account' => $account
        ]);
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
            $user = Auth::user();
            // Send log
            ModelsLog::create([
                'account' => $user->name . " • " . $user->role,
                'description' => 'Logged in to the system',
                'type' => 'Account',
                'detail' => $user->toJson()
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
        $account = Account::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role')
        ]);

        // Create new log file
        ModelsLog::create([
            'account' => Auth::user()->name . " • " . Auth::user()->role,
            'description' => 'Added new user to the system: '.$request->input('name'),
            'type' => 'Account',
            'detail' => $account->toJson()
        ]);

        return response('Account created successfully');
    }

    // Forgot Password
    public function sendResetPasswordLink(ForgotPasswordRequest $request){
        // Validate the request
        $request->validated();
        
        // Find the user by email
        $user = Account::where('email', $request->input('email'))->first();
        
        // Check if the user exists
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $user->generateResetPasswordToken();

        $resetPasswordToken = $user->reset_password_token()->first(); 

        if (!$resetPasswordToken) {
            return response()->json(['message' => 'Failed to generate reset password token.'], 500);
        }

        $verificationLinkToken = $resetPasswordToken->token;

        Mail::to($user->email)->queue(new ResetPassword($verificationLinkToken));

        // Create a log entry for the action
        ModelsLog::create([
            'account' => $user->name . ' • ' . $user->role,
            'description' => 'Sent reset password link to email with address: ' . $user->email,
            'type' => 'Account',
            'detail' => $user->toJson()
        ]);

        // Return a response indicating the link has been sent
        return response('Reset password link sent to ' . $user->email);
    }


    // Reset Password
    public function resetPassword(Request $request){
        // Validate request
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'password.required' => 'Password field is required!',
            'password.min' => 'Password should contain minimum of 8 characters!',

            'password_confirmation.required' => 'Password confirmation does not match the inputted password!'
        ]);

        // Create password
        $token = ResetPasswordToken::where('token', $request->input('reset_password_token'))->first();
        $account = $token->account()->first();

        $account->password = Hash::make($request->input('password'));
        $token->used = true;
        $token->save();
        $account->save();

        // Create new log
        ModelsLog::create([
            'account' => $account->name . " • " . $account->role,
            'description' => 'Resetted password',
            'type' => 'Account',
            'detail' => $account->toJson()
        ]);

        return response('Password successfully resetted!');
    }

    // Logout
    public function logout(Request $request){
        if (Auth::check()){
            // Create new log
            ModelsLog::create([
                'account' => Auth::user()->name . " • " . Auth::user()->role,
                'description' => 'Logged out to the system',
                'type' => 'Account',
                'detail' => Auth::user()->toJson()
            ]);
    
            // Logout
            Auth::logout();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

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
            'description' => 'Deactivated account: '.$account->name,
            'type' => 'Account',
            'detail' => $account->toJson()
        ]);

        return response('Account deactivated successfully!');
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
            'description' => 'Edited account role of '.$account->name.' to '.$account->role,
            'type' => 'Account',
            'detail' => $account->toJson()
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
            'description' => 'Reactivated account: '.$account->name,
            'type' => 'Account',
            'detail' => $account->toJson()
        ]);
    }

    public function editAccess(Request $request){
        // Get the settings
        $settings = Settings::all()->first();
        $access = [];

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
            $secretary->save();
        }   

        $access['secretary'] = $request->secretaryAccesses;

        foreach ($assistants as $assistant) {
            $assistant->canUpload     = filter_var($request->assistantAccesses[0], FILTER_VALIDATE_BOOLEAN);
            $assistant->canEdit       = filter_var($request->assistantAccesses[1], FILTER_VALIDATE_BOOLEAN);
            $assistant->canMove       = filter_var($request->assistantAccesses[2], FILTER_VALIDATE_BOOLEAN);
            $assistant->canArchive    = filter_var($request->assistantAccesses[3], FILTER_VALIDATE_BOOLEAN);
            $assistant->canDownload   = filter_var($request->assistantAccesses[4], FILTER_VALIDATE_BOOLEAN);
            $assistant->canPrint      = filter_var($request->assistantAccesses[5], FILTER_VALIDATE_BOOLEAN);
            
            $assistant->save();

        }   

        $access['assistant'] = $request->assistantAccesses;

        foreach ($clerks as $clerk) {
            $clerk->canUpload     = filter_var($request->clerkAccesses[0], FILTER_VALIDATE_BOOLEAN);
            $clerk->canEdit       = filter_var($request->clerkAccesses[1], FILTER_VALIDATE_BOOLEAN);
            $clerk->canMove       = filter_var($request->clerkAccesses[2], FILTER_VALIDATE_BOOLEAN);
            $clerk->canArchive    = filter_var($request->clerkAccesses[3], FILTER_VALIDATE_BOOLEAN);
            $clerk->canDownload   = filter_var($request->clerkAccesses[4], FILTER_VALIDATE_BOOLEAN);
            $clerk->canPrint      = filter_var($request->clerkAccesses[5], FILTER_VALIDATE_BOOLEAN);

            $clerk->save();
        }   

        $access['clerk'] = $request->clerkAccesses;
        
        $settings->access = $access;
        $settings->save();
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

    // Edit Functions
    // Edit Profile Name
    public function editName(Request $request){
        $request->validate([
            'name' => 'required|string'
        ],[
            'name.required' => 'Profile name empty!'
        ]);

        $user = Auth::user();

        $name = $user->name;
        $user->name = $request->input('name');
        
        $user->save();

        ModelsLog::create([
            'account' => Auth::user()->name.' • '.Auth::user()->role,
            'description' => 'Change profile name from '.$name.' to '.Auth::user()->name,
            'type' => 'Account',
            'detail' => $user->toJson()
        ]);

        return response()->json([
            'name' => Auth::user()->name.' • '.Auth::user()->role
        ]);
    }

    // Edit Email
    public function editEmail(Request $request){
        $request->validate([
            'email' => 'required|email|max:255|unique:accounts'
        ],[
            'email.required' => 'Email empty!',
            'email.email' => 'Email inputted is not an email address!',
        ]);

        $user = Auth::user();
        $email = $user->email;
        $user->email = $request->input('email');

        // Unverify the user
        $user->email_verified_at = null;

        $user->save();

        ModelsLog::create([
            'account' => Auth::user()->name.' • '.Auth::user()->role,
            'description' => 'Changed email from '.$email.'to'.Auth::user()->email,
            'type' => 'Account',
            'detail' => $user->toJson()
        ]);

        return response()->json([
            'email' => Auth::user()->name.' • '.Auth::user()->role
        ]);
    }

    public function sendEmailVerificationLink(){
        $user = Account::find(Auth::user()->id);
        
        $user->generateVerifyEmailToken();

        $email_verification_token = $user->email_verification_token()->first();
        $verificationLinkToken = $email_verification_token->token;

        Mail::to($user->email)
            ->queue(new VerifyEmail($verificationLinkToken));

        ModelsLog::create([
            'account' => Auth::user()->name.' • '.Auth::user()->role,
            'description' => 'Sent verification to email with address: '.Auth::user()->email,
            'type' => 'Account',
            'detail' => $user->toJson()
        ]);

        return response()->json([
            'success' => 'Verification link successfully!'
        ]);
    }

    public function verifyEmail(Request $request){
        $token = EmailVerificationToken::where('token', $request->token)->first();
        if($token->used !== true){
            $account = $token->account()->first();
    
            $account->email_verified_at = now();
            $account->email_verification_token = null;
            $token->used = true;
            $account->save();
    
            return redirect()->route('show.login');
        } else {
            return redirect()->route('show.cantVerifyEmail');
        }
    }
}
