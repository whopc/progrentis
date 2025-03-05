<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['name', 'grade_id'];

    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at'];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
    public function students()
    {
        return $this->belongsToMany(Student::class);
    }
}
