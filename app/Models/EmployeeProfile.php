<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeProfile extends Model
{
    protected $fillable = [
        'user_id',
        'phone_number',
        'employee_code',
        'department',
        'designation',
    ];
}