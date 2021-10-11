<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateBadge extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id','width','high','bg_color','bg_image','is_locked','creator'
    ];
}
