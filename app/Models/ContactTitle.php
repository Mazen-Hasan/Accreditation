<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contactTitle extends Model
{
    use HasFactory;

    protected $fillable = [
         'contact_id','title_id','status'
    ];
}
