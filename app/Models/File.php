<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $table = "files";
    protected $fillable = [
        'model',
        'model_id',
        'file'
    ];

    public static function rules()
    {
        return [
            'model_id' => 'required',
            'file' => 'required',
        ];
    }
}