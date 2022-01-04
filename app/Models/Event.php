<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'period', 'location', 'size', 'organizer', 'owner', 'event_type', 'accreditation_period',
        'status', 'approval_option', 'event_form', 'creation_date', 'creator', 'event_start_date', 'event_end_date', 'accreditation_start_date',
        'accreditation_end_date'
    ];

}
