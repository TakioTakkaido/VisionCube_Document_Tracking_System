<?php

namespace App\Http\Controllers;

use App\Events\GeneratedReport;
use App\Models\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller {
    // Generate
    public function generate(Request $request) {
        // Upload the file to google drive
        $success = true;
        $fileLink = "";

        // If successful, create a report
        if ($success){
            Report::create([
                'name' => $request->input('name'),
                'file' => $fileLink
            ]);

            GeneratedReport::dispatch();

            return response()->json([
                'success' => 'Report created successfully!'
            ]);
        } else {
            // Else, create an error message
            return response()->json([
                'error' => 'File upload unsuccessful'
            ], 422);
        }
    }

    public function download(Request $request) {
        $report = Report::find($request->id);

        return response()->json([
            'fileLink'  => $report->file
        ]);
    }

    public function showAll(Request $request) {
        // Get all reports
        $reports = Report::all();

        return response()->json([
            'reports' => $reports
        ]);
    }
}
