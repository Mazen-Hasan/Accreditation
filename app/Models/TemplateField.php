<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateField extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'template_id', 'label_ar', 'label_en', 'mandatory', 'min_char', 'max_char', 'field_order', 'field_type_id'
    ];
}
