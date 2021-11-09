<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyStaff extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'company_id',
        'status',
        'identifier'
    ];
}
