<?php namespace Latihan\Latihan\Models;

use Model;

class Student extends Model
{
    protected $table = 'latihan_students';

    protected $fillable = [
        'name',
        'subject',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public $rules = [
        'name'    => 'required|string|max:255',
        'subject' => 'required|string|max:255',
    ];
}
