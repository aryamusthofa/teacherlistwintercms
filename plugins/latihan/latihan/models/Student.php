<?php namespace Latihan\Latihan\Models;

use Model;

class Student extends Model
{
    protected $table = 'latihan_students';

    protected $fillable = [
        'name',
        'subject',
        'is_active',
        'is_verified'
    ];
}
