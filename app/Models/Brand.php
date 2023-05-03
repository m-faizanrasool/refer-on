<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
    ];

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function blacklistedTasks()
    {
        return $this->hasMany(BlacklistedTasks::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            $key = Str::slug($brand->name);

            // Check if the key already exists, and if so, append a number to make it unique
            $count = 1;
            while (static::where('key', $key)->exists()) {
                $key = Str::slug($brand->name) . '-' . $count++;
            }

            $brand->key = $key;
        });
    }
}
