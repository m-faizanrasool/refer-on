<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlacklistedTasks extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded  = [];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
