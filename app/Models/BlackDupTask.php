<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlackDupTask extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'brand_id',
        'submitter_id',
        'code',
        'status',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function submitter()
    {
        return $this->belongsTo(User::class);
    }
}
