<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Section;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //      $Grades = Grade::with(['Sections'])->get();

    // $list_Grades = Grade::all();

    // return view('pages.sections.sections',compact('Grades','list_Grades'));

    // }
    public function index()
{
    // يجلب المراحل مع الأقسام في استعلام واحد
    $Grades = Grade::with('Sections')->get();

    // أُعيدُ استخدام نفس التجميعة للقائمة المنسدلة
    // يمكنك حتى الاستغناء عن $list_Grades تمامًا واستخدام $Grades في الـ Blade
    $list_Grades = $Grades;

    return view('pages.sections.sections', compact('Grades', 'list_Grades'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

     $request->validate([
            'name_section_Ar' => 'required|string|max:255',
            'name_section_En' => 'required|string|max:255',
            'grade_id' => 'required|exists:grades,id',
             'class_id' => 'required|exists:classrooms,id',
        ]);

        $Sections = new Section();
        $Sections->name_section = ['ar' => $request->name_section_Ar, 'en' => $request->name_section_En];
        $Sections->grade_id = $request->grade_id;
         $Sections->class_id = $request->class_id;
        // $Sections->Status = 1;
         if(isset($request->Status)) {
        $Sections->Status = 1;
      } else {
        $Sections->Status = 2;
      }

        $Sections->save();

        toastr()->success(trans('messages.success'));
        return redirect()->route('sections.index');
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name_section_Ar' => 'required|string|max:255',
            'name_section_En' => 'required|string|max:255',
            'grade_id' => 'required|exists:grades,id',
             'class_id' => 'required|exists:classrooms,id',
        ]);
      $Sections = Section::findOrFail($request->id);

      $Sections->name_section = ['ar' => $request->name_section_Ar, 'en' => $request->name_section_Ar];
      $Sections->grade_id = $request->grade_id;
      $Sections->class_id = $request->class_id;

      if(isset($request->Status)) {
        $Sections->Status = 1;
      } else {
        $Sections->Status = 2;
      }

      $Sections->save();
      toastr()->success(trans('messages.Update'));

      return redirect()->route('sections.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

public function getclasses($id)
{

     $list_classes = Classroom::where("grade_id", $id)->pluck("name_class", "id");

        return $list_classes;
}
// app/Http/Controllers/SectionController.php
public function massUpdate(Request $request)
{
    $validated = $request->validate([
        'sections'                => 'required|array|min:1',
        'sections.*.id'           => 'required|integer|exists:sections,id',
        'sections.*.name_ar'      => 'required|string|max:255',
        'sections.*.grade_id'     => 'required|integer|exists:grades,id',
        // status اختيارى (checkbox غير المؤشر لا يُرسل)
    ]);

    foreach ($validated['sections'] as $sec) {
        Section::where('id', $sec['id'])->update([
            'name_section' => $sec['name_ar'],
            'grade_id'     => $sec['grade_id'],
            'Status'       => isset($sec['status']) ? 1 : 0,
        ]);
    }

    return back()->with('success', __('messages.saved_successfully'));
}

}
