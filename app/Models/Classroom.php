<?php

namespace App\Models;
use Spatie\Translatable\HasTranslations;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model

{
     use HasTranslations;
     protected $fillable = ['name_class', 'grade_id'];

    public $translatable = ['name_class'];
 // العلاقة بين الصفوف والمراحل الدراسية (كل صف ينتمي لمرحلة)
    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    // يمكن أيضًا إضافة علاقة الأقسام في حالة الاستخدام
    public function sections()
    {
        return $this->hasMany(Section::class, 'class_id');
    }
    //  protected $fillable = ['name_en', 'name_ar', 'grade_id'];

    // public function grade()
    // {
    //     return $this->belongsTo(Grade::class, 'grade_id');
    // }

    // public function sections()
    // {
    //     return $this->hasMany(Section::class, 'class_id');
    // }

}
