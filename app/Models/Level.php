<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = ['name'];

    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at'];

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

}
