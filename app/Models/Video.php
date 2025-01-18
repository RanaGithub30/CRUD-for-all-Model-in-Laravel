<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = "videos";
    protected $fillable = [
        'name',
        'description'
    ];

    public static function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required',
        ];
    }
}