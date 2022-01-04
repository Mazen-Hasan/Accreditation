<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory, Uuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey   = 'id';

    protected $fillable = [
        'name', 'status', 'is_locked', 'creator'
    ];

}
