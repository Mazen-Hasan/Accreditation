<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateFieldElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'value_ar', 'value_en', 'value_id', 'order', 'template_field_id'
    ];
}
