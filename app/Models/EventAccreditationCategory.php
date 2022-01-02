<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventAccreditationCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id','accreditation_category_id', 'size', 'status'
    ];
}
