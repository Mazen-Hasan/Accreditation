<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'middle_name', 'last_name', 'email', 'telephone', 'mobile', 'status', 'password', 'account_id', 'company_admin_id', 'company_id'
    ];
}
