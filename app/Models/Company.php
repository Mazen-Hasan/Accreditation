<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

        protected $fillable = [
        'name', 'address', 'telephone', 'website', 'focal_point_id','company_admin_id',
        'country_id', 'city_id', 'category_id','size' , 'event_id' , 'need_management' , 'status'
    ];
}
