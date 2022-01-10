<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory, Uuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey   = 'id';

    protected $fillable = [
        'name', 'address', 'telephone', 'website', 'focal_point_id', 'company_admin_id', 'subCompany_id',
        'country_id', 'city_id', 'category_id', 'size', 'event_id', 'need_management', 'status','parent_id'
    ];
}
