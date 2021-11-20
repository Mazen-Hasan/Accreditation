<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateBadgeBackground extends Model
{
    use HasFactory;

    protected $fillable = [
        'badge_id','accreditation_category_id','bg_image', 'creator'
    ];
}
