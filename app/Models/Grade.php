<?php

namespace Grade;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Grade extends Model
{
     use HasTranslations;

    protected $fillable = ['name', 'notes'];

    public $translatable = ['name', 'notes'];

    protected $table = 'Grades';
    public $timestamps = true;

    public function classrooms()
{
    return $this->hasMany(Classroom::class);
}

 public function sections()
    {
        return $this->hasMany(Section::class, 'grade_id');
    }


}

