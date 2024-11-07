<?php

// app/Models/Employee.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'position', 'email', 'phone', 'work_schedule'];

    // Transform work_schedule to and from JSON
    protected $casts = [
        'work_schedule' => 'array',
    ];
}
