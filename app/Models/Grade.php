<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = ['name', 'level_id'];

    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at'];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
