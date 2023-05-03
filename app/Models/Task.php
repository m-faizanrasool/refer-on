<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\Node\Expr\FuncCall;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'key',
        'parent_id',
        'brand_id',
        'country_id',
        'submitter_id',
        'executor_id',
        'website',
        'summary',
        'submitter_credits',
        'executor_credits',
        'fulfilled_at',
        'status',
        'tags',
    ];

    protected $appends = [
        'brand_name',
        'country_name',
        'executor_name',
    ];

    public function getBrandNameAttribute()
    {
        return $this->brand->name;
    }

    public function getCountryNameAttribute()
    {
        return $this->country->name;
    }

    public function getExecutorNameAttribute()
    {
        return $this->executor ? $this->executor->username : "";
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitter_id');
    }

    public function childs()
    {
        return $this->hasMany(Task::class, 'parent_id', 'id');
    }

    public function executor()
    {
        return $this->belongsTo(User::class, 'executor_id');
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
