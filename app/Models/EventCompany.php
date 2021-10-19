<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'company_id', 'focal_point_id','parent_id','status','id','size','need_management'
    ];
}
