<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    public function submitter(){
        return $this->belongsTo(User::class, 'submitter_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }
}
