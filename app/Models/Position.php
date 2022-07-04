<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;
    protected $table = 'position';
    protected $fillable = [
        'department_id',
        'position_name',
    ];

    public function department()
    {
        return $this->belongsTo('App\Models\Department');
    }

    public function level()
    {
        return $this->belongsToMany('App\Models\Level');
    }
}
