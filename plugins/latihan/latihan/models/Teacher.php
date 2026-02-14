<?php namespace Latihan\Latihan\Models;

use Model;

class Teacher extends Model
{
    protected $table = 'latihan_teachers';

    protected $fillable = [
        'name',
        'subject',
        'is_active'
    ];
}