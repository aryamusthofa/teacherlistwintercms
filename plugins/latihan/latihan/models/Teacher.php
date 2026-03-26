<?php namespace Latihan\Latihan\Models;

use Model;

class Teacher extends Model
{
    protected $table = 'latihan_teachers';

    protected $fillable = [
        'name',
        'subject',
        'is_active',
        'is_verified',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'is_verified' => 'boolean',
    ];

    public $rules = [
        'name'    => 'required|string|max:255',
        'subject' => 'required|string|max:255',
    ];
}