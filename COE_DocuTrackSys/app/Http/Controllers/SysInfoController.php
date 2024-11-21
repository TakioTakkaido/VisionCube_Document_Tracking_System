<?php

namespace App\Http\Controllers;

use App\Models\SysInfo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SysInfoController extends Controller {
    public function update(Request $request){
        $info = SysInfo::all()->first();

        if ($request->filled('name')) {
            $info->name = $request->input('name');
        }
        
        if ($request->filled('about')) {
            $info->about = $request->input('about');
        }
        
        if ($request->filled('mission')) {
            $info->mission = $request->input('mission');
        }
        
        if ($request->filled('vision')) {
            $info->vision = $request->input('vision');
        }

        // Add similar checks for logo and favicon as needed

        $info->save();

        return response('System information changed successfully');
    }

}
