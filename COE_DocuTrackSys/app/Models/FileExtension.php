<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileExtension extends Model {
    use HasFactory;

    protected $fillable = [
        'value',
        'checked'
    ];

    public static function getFileExtensions() : string {
        // Get all file extensions
        $fileExtensions = FileExtension::all();
        $fileExtensionsStr = "";
        
        for ($i=0; $i < sizeof($fileExtensions); $i++) { 
            if($fileExtensions[$i]->checked == true) {
                $fileExtensionsStr .= $fileExtensions[$i]->value;

                if($i + 1 != sizeof($fileExtensions)){
                    $fileExtensionsStr .= ',';
                }
            }
        }
        return $fileExtensionsStr;
    }
}