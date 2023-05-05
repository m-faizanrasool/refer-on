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
        'code',
        'parent_id',
        'brand_id',
        'submitter_id',
        'executor_id',
        'fulfilled_at',
        'status',
        'tags',
    ];

    protected $appends = [
        'brand_name',
        'executor_name',
    ];

    public function getBrandNameAttribute()
    {
        return $this->brand->name;
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
}
