<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = "images";
    protected $fillable = [
        'video_id',
        'img'
    ];

    public static function rules()
    {
        return [
            'video_id' => 'required',
            'img' => 'required',
        ];
    }
}