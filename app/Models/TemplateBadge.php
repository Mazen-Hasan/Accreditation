<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateBadge extends Model
{
    use HasFactory, Uuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey   = 'id';

    protected $fillable = [
        'template_id', 'width', 'high', 'bg_image', 'is_locked', 'creator'
    ];
}
