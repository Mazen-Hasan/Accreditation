<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateBadgeFields extends Model
{
    use HasFactory;

    protected $fillable = [
        'badge_id', 'template_field_id', 'template_field_name', 'position_x', 'position_y', 'size', 'text_color', 'font', 'bg_color'
    ];
}
