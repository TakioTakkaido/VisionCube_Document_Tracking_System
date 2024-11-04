<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttachmentController extends Controller {
    public function show(Request $request){
        $attachment = Attachment::find($request->id);

        return response()->json([
            'fileLink' => $attachment->file(),
        ]);
    }
}
