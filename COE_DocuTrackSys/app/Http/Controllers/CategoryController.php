<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Edit category
    public function update(Request $request){
        // Validate the request

        // Get category by id
        // Check whether the status already exists or not
        if ($request->id != null){
            // Status exists, find the status
            $category = Category::find($request->id);

            // Change value
            $category->value = $request->value;

            // Save
            $category->save();

            // Log
        } else {
            // Category doesn't exist
            // Create category
            Category::create([
                'value' => $request->input('value')
            ]);

            // Log
        }

        

        // Return success
        return response()->json([
            'success' => 'Category edited successfully.'
        ]);
    }

    // Delete category
    public function delete(Request $request){
        $category = Category::find($request->id);

        $category->delete();

        // Log

        // Return success
        return response()->json([
            'success' => 'Category delete successfully.'
        ]);
    }
}
