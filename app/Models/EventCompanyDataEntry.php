<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCompanyDataEntry extends Model
{
    use HasFactory;

    protected $fillable = [
         'status', 'company_id' , 'event_id','event_companies_id','data_entry_id'
    ];
}
