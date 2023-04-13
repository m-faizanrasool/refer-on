<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'key',
        'brand_id',
        'country_id',
        'submitter_id',
        'executor_id',
        'website',
        'summary',
        'submitter_credits',
        'executor_credits',
        'status',
    ];

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitter_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
