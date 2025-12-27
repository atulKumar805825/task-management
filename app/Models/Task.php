<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Task extends Model {
    use SoftDeletes,HasFactory;

    protected $fillable = [
        'title','description','status','priority','due_date','user_id'
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function assignees() {
        return $this->belongsToMany(
            User::class,
            'task_assignments',
            'task_id',
            'assigned_to'
        );
    }
}

