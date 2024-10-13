<?php

// VISION CUBE SOFTWARE CO. 
// Routes
// Facilitates the routing of the system and its corresponding pages in the system.
// Contributor/s: 
// Calulut, Joshua Miguel C.

use App\AccountRole;
use App\DocumentCategory;
use App\DocumentStatus;
use App\DocumentType;

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FileExtensionController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ParticipantGroupController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TypeController;
use App\Http\Middleware\NoCache;
use App\Http\Middleware\NoDirectAccess;
use App\Http\Middleware\VerifyAccount;
use App\Http\Middleware\VerifyAccountRole;
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Route;

// All accounts here shall undergo the middleware to check
// whether the the user is logged in or not
// Middleware: Check Login Token
Route::middleware([VerifyAccount::class])->group(function() {
    // Display Routes
    Route::name('show.')->group(function(){
        // Display: Login, Landing Page
        Route::get('/', function () {
            return view('account.login');
        })->name('login');

        // Display: Create Account
        Route::get('/create', function(){
            return view('account.create');
        })->name('create');

        // Display: Forgot Password
        Route::get('/password/forgot', function(){
            return view('account.forgotPassword');
        })->name('forgotPassword');

        // Display: Reset Password
        Route::get('/password/reset', function(){
            return view('account.resetPassword');
        })->name('resetPassword');

        // Display: Login Admin
        Route::get('/admin', function () {
            return view('account.admin.login');
        })->name('login-admin');

        // Display Dashboard
        Route::get('/dashboard', function(){
            return view('account.dashboard');
        })->name('dashboard');
    });
});

// Middleware: No Direct Access
// Checks if the user directly access the functions using the link
// If yes, dont redirect or perform the functions and send an error event
Route::middleware([NoDirectAccess::class, VerifyAccountRole::class.':Admin'])->group(function(){
    // Account Routes ('/account')
    Route::name('account.')->group(function(){
        Route::controller(AccountController::class)->group(function(){
            Route::prefix('/account')->group(function(){
                // ROLES: ADMIN
                // Admin Functions
                // View All Accounts
                Route::get('/show/all', 'showAllActiveAccounts')
                ->name('showAllActiveAccounts');
                
                // View All Deactivated Accounts
                Route::get('/show/deact', 'showAllDeactivatedAccounts')
                ->name('showAllDeactivatedAccounts');

                // Edit Account Role
                Route::post('/edit/{id}/{role}', 'editAccountRole')
                ->name('editAccountRole');

                // Reactivate Account
                Route::post('/reactivate/{id}', 'reactivate')
                ->name('reactivate');

                // Deactivate Account
                Route::post('/deactivate/{id}', 'deactivate')
                ->name('deactivate');
            });
        });
    });
});

Route::middleware([NoDirectAccess::class, VerifyAccountRole::class.':Admin'])->group(function(){
    // Account Routes ('/account')
    Route::name('log.')->group(function(){
        Route::controller(LogController::class)->group(function(){
            Route::prefix('/log')->group(function(){
                // ROLES: ADMIN
                // Admin Functions
                // View All Logs
                Route::get('/show/all', 'showAllLogs')
                ->name('showAll');
            });
        });
    });
});

Route::middleware(NoDirectAccess::class)->group(function(){
    // Account Routes ('/account')
    Route::name('account.')->group(function(){
        Route::controller(AccountController::class)->group(function(){
            Route::prefix('/account')->group(function(){
                // ROLES: NOT REQUIRED
                // View Account
                Route::get('/show/{id}', 'show')
                ->name('show');

                // Send Login Credentials
                Route::post('/login', 'login')
                ->name('login');

                // Send Create Account Credentials
                Route::post('/create', 'create')
                ->name('create');
                
                // Send Forgot Password Credentials
                Route::post('/forgot-password', 'forgotPassword')
                ->name('forgotPassword');

                // Send Reset Password Credentials
                Route::post('/reset-password', 'resetPassword')
                ->name('resetPassword');

                // Send Log In Admin
                Route::post('/login/admin', 'loginAdmin')
                ->name('loginAdmin');

                // Log Out
                Route::post('/logout', 'logout')
                ->name('logout');
            });
        });
    });

    // Document Routes ('/document')
    Route::name('document.')->group(function(){
        Route::controller(DocumentController::class)->group(function(){
            Route::prefix('/document')->group(function(){
                // ROLES: ALL, EXCEPT GUEST
                Route::middleware(VerifyAccountRole::class.':Admin,Secretary,Clerk')->group(function(){
                    // Get Incoming Documents
                    Route::get('view/incoming', 'showIncoming')
                    ->name('showIncoming');

                    // Get Outgoing Documents
                    Route::get('view/outgoing', 'showOutgoing')
                    ->name('showOutgoing');

                    // Get Archived Documents, by Document Type
                    Route::get('view/archived', 'showArchived')
                    ->name('showArchived');

                    // Get Document View
                    Route::get('view/{id}', 'show')
                    ->name('show');

                    // Get Document Versions
                    Route::get('view/{id}/versions', 'showDocumentVersions')
                    ->name('showDocumentVersions');

                    // Download Document
                    Route::get('download/{id}', 'download')
                    ->name('download');
                });

                // ROUTE: ALL, EXCEPT ARCHIVIST AND GUEST
                Route::middleware(VerifyAccountRole::class.':Admin,Secretary,Clerk')->group(function(){
                    // Edit Document
                    Route::post('edit/{id}', 'edit')
                    ->name('edit');

                    // Move Document
                    Route::post('move', 'move')
                    ->name('move');
                });


                // ROLE: ADMIN AND SECRETARY ONLY
                Route::middleware(VerifyAccountRole::class.':Admin,Secretary')->group(function(){
                    // Upload Document
                    Route::post('upload', 'upload')
                    ->name('upload');
                });
            });
        }); 
    });
});

Route::middleware(NoDirectAccess::class)->group(function(){
    Route::name('display.')->group(function(){
        Route::get('/display/table', function(){
            return view('components.dashboard.table');
        })
        ->name('table');
    });
});

// System Settings Route for AJAX Requests
Route::post('/participant/update', [ParticipantController::class, 'update'])
->name('participant.update');

Route::post('/participant/delete', [ParticipantController::class, 'delete'])
->name('participant.delete');

Route::post('/participantGroup/update', [ParticipantGroupController::class, 'update'])
->name('participantGroup.update');

Route::post('/participantGroup/delete', [ParticipantGroupController::class, 'delete'])
->name('participantGroup.delete');

Route::post('/participantGroup/updateParticipantGroupMembers', [ParticipantGroupController::class, 'updateParticipantGroupMembers'])
->name('participantGroup.updateParticipantGroupMembers');;

Route::get('/participantGroup/getParticipantGroupMembers/{id}', [ParticipantGroupController::class, 'getParticipantGroupMembers'])
->name('participantGroup.getParticipantGroupMembers');

Route::post('/status/update', [StatusController::class, 'update'])
->name('status.update');

Route::post('/status/delete', [StatusController::class, 'delete'])
->name('status.delete');

Route::post('/type/update', [TypeController::class, 'update'])
->name('type.update');

Route::post('/type/delete', [TypeController::class, 'delete'])
->name('type.delete');

Route::post('/category/update', [CategoryController::class, 'update'])
->name('category.update');

Route::post('/category/delete', [CategoryController::class, 'delete'])
->name('category.delete');

Route::post('/fileExtensions/update', [FileExtensionController::class, 'update'])
->name('fileExtension.update');

Route::post('/account/update/access', [AccountController::class, 'editAccess'])
->name('account.editAccess');

Route::get('/document/preview/{id}', [DocumentController::class, 'preview'])
->name('document.preview');



require __DIR__.'/auth.php';