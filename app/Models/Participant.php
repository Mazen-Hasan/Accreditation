<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'first_name_ar',
        'last_name',
        'last_name_ar',
        'nationality',
        'email',
        'mobile',
        'position',
        'religion',
        'address',
        'birthdate',
        'gender',
        'company',
        'subCompany',
        'passport_number',
        'id_number',
        'class',
        'accreditation_category'
    ];
}
