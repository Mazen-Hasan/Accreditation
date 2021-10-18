<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCompanyAccreditaionCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'company_id', 'accredit_cat_id', 'parent_id', 'size', 'status','event_compnay_id'
    ];
}
