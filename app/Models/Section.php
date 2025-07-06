<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
      use HasTranslations;
    public $translatable = ['name_section'];
    //  use HasFactory;

    // protected $fillable = ['name_section', 'status', 'grade_id', 'class_id'];
     protected $fillable = ['name_section', 'status', 'grade_id', 'class_id'];

    // العلاقات
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function My_classs()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }
}
