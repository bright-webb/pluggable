<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
    protected $fillable = ['name', 'service_provider', 'description'];
    use HasFactory;
}
