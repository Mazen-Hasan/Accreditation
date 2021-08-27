<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'period','event_admin','location','size','organizer','owner','event_type','accreditation_period',
        'status','approval_option','security_officer','event_form','creation_date','creator'
    ];

}
