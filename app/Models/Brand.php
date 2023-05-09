<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'country_id',
        'website',
        'summary',
        'submitter_credits',
        'executor_credits',
    ];

    public function getCountryNameAttribute()
    {
        return $this->country->name;
    }

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function blacklistedTasks()
    {
        return $this->hasMany(BlacklistedTasks::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            $brand->key = Str::lower(str_replace(' ', '_', $brand->name));
        });
    }
}
