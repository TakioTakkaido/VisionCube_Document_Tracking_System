<?php

// VISION CUBE SOFTWARE CO. 
// Routes
// Facilitates the routing of the system and its corresponding pages in the system.
// Contributor/s: 
// Calulut, Joshua Miguel C.

// Controllers
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentVersionController;
use App\Http\Controllers\FileExtensionController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ParticipantGroupController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TypeController;

// Middlewares
use App\Http\Middleware\NoDirectAccess;
use App\Http\Middleware\UnderMaintenance;
use App\Http\Middleware\VerifyAccount;
use App\Http\Middleware\VerifyDeactivated;

use Illuminate\Support\Facades\Route;

// DISPLAY ROUTES
// Middleware: Check Login Token
Route::middleware([VerifyAccount::class])->group(function(){
    // Display Routes
    Route::name('show.')->group(function(){
        // Display: Login, Landing Page
        Route::get('/', function () {
            return view('account.login');
        })->name('login');

        // Middleware: Check if Under Maintenance, Check if Account is Deactivated
        Route::middleware([UnderMaintenance::class, VerifyDeactivated::class])->group(function(){
            // Display Dashboard
            Route::get('/dashboard', function(){
                return view('account.dashboard');
            })->name('dashboard');
        });
    });
});

// Middleware: No Direct Access
Route::middleware([NoDirectAccess::class])->group(function() {
    // Display Routes
    Route::name('show.')->group(function(){
        // Display: Forgot Password
        Route::get('/password/forgot', function(){
            return view('account.forgotPassword');
        })->name('forgotPassword');

        // Display: Reset Password
        Route::get('/password/reset', function(){
            return view('account.resetPassword');
        })->name('resetPassword');

        // Under Maintenance
        Route::get('/maintenance', function(){
            return view('account.underMaintenance');
        })->name('underMaintenance');        

        // Deactivated Account Page
        Route::get('/deactivated', function(){
            return view('account.deactivated');
        })->name('deactivated'); 
    });

    // DASHBOARD ROUTES
    // Account Routes
    Route::name('account.')->group(function(){
        Route::controller(AccountController::class)->group(function(){
            Route::prefix('/account')->group(function(){
                // Show All Active Accounts
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

                // Edit Account Accesses
                Route::post('/update/access', 'editAccess')
                ->name('editAccess');
            });
        });
    });

    // Document Routes
    Route::name('document.')->group(function(){
        Route::controller(DocumentController::class)->group(function(){
            Route::prefix('/document')->group(function(){
                // Get All Documents
                Route::get('view/all/{category}', 'showAll')
                ->name('showAll');

                // Get Document View
                Route::get('view/{id}', 'show')
                ->name('show');

                // Get Document Versions
                Route::get('view/{id}/versions', 'showDocumentVersions')
                ->name('showDocumentVersions');

                // Get Document Attachments
                Route::get('view/{id}/attachments', 'showAttachments')
                ->name('showAttachments');

                // Download Document
                Route::get('download/{id}', 'download')
                ->name('download');

                // Document Statistics
                Route::get('stats', 'getDocumentStatistics')
                ->name('getStatistics');

                // Edit Document
                Route::post('edit/{id}', 'edit')
                ->name('edit');

                // Move Document
                Route::post('move', 'move')
                ->name('move');

                // Upload Document
                Route::post('upload', 'upload')
                ->name('upload');

                // Document Preview
                Route::get('/preview/{id}', 'preview')
                ->name('preview');
            });
        }); 
    });

    // Document Version Routes
    Route::name('version.')->group(function(){
        Route::controller(DocumentVersionController::class)->group(function(){
            Route::prefix('/version')->group(function(){
                Route::get('show/{id}', 'show')
                ->name('show');
            });
        });
    });
            
    // Attachment Routes
    Route::name('attachment.')->group(function(){
        Route::controller(AttachmentController::class)->group(function(){
            Route::prefix('/attachment')->group(function(){
                Route::get('show/{id}', 'show')
                ->name('show');
            });
        });
    });

    // Log Routes
    Route::name('log.')->group(function(){
        Route::controller(LogController::class)->group(function(){
            Route::prefix('/log')->group(function(){
                // View All Logs
                Route::get('/show/all', 'showAllLogs')
                ->name('showAll');

                Route::get('/view/{id}', 'show')
                ->name('show');
            });
        });
    });

    // Settings Routes
    Route::name('settings.')->group(function(){
        Route::controller(SettingsController::class)->group(function(){
            Route::prefix('/setings')->group(function(){
                // Update Settings
                Route::post('update', 'updateMaintenanceStatus')
                ->name('update');

                // Get Maintenance Status
                Route::get('maintenance', 'getMaintenanceStatus')
                ->name('getMaintenance');
            });
        });
    });

    // System Settings Routes
    // Participants
    Route::name('participant.')->group(function(){
        Route::controller(ParticipantController::class)->group(function(){
            Route::prefix('/participant')->group(function(){
                Route::post('/update', 'update')
                ->name('update');

                Route::post('/delete', 'delete')
                ->name('delete');
            });
        });
    });

    // Participant Groups
    Route::name('participantGroup.')->group(function(){
        Route::controller(ParticipantGroupController::class)->group(function(){
            Route::prefix('/participantGroup')->group(function(){
                Route::post('/update', 'update')
                ->name('update');

                Route::post('/delete', 'delete')
                ->name('delete');

                Route::post('updateParticipantGroupMembers', 'updateParticipantGroupMembers')
                ->name('updateParticipantGroupMembers');

                Route::get('getParticipantGroupMembers/{id}', 'getParticipantGroupMembers')
                ->name('getParticipantGroupMembers');
            });
        });
    });

    // Type
    Route::name('type.')->group(function(){
        Route::controller(TypeController::class)->group(function(){
            Route::prefix('/type')->group(function(){
                Route::post('/update', 'update')
                ->name('update');

                Route::post('/delete', 'delete')
                ->name('delete');
            });
        });
    });

    // Status
    Route::name('status.')->group(function(){
        Route::controller(StatusController::class)->group(function(){
            Route::prefix('/status')->group(function(){
                Route::post('/update', 'update')
                ->name('update');

                Route::post('/delete', 'delete')
                ->name('delete');
            });
        });
    });

    // File Extension
    Route::name('fileExtension.')->group(function(){
        Route::controller(FileExtensionController::class)->group(function(){
            Route::prefix('/fileExtension')->group(function(){
                Route::post('/update', 'update')
                ->name('update');
            });
        });
    });
});

require __DIR__.'/auth.php';