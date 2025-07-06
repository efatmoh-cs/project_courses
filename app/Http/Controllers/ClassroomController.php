<?php

namespace App\Http\Controllers;
use App\Models\Classroom;
use App\Models\Grade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
          $My_Classes = Classroom::with('grade')->get();
           $Grades = Grade::all();
        return view('classrooms', compact('My_Classes','Grades'));
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
//     public function store(Request $request)
// {
//     $request
//     ->validate([
//         'List_Classes.*.name.ar' => 'required|string|max:255|unique:grades,name->ar',
//         'List_Classes.*.name.en' => 'required|string|max:255|unique:grades,name->en',
//         'List_Classes.*.grade_id' => 'required|exists:grades,id',
//     ], [
//         'List_Classes.*.name.ar.unique' => trans('Grades_trans.exists'),
//         'List_Classes.*.name.en.unique' => trans('Grades_trans.Name.unique'),
//         'List_Classes.*.grade_id.required' => trans('validation.required'),
//     ]);

//     foreach ($request->List_Classes as $classData) {
//         Classroom::create([
//             'name' => $classData['name'], // ['en' => ..., 'ar' => ...]
//             'grade_id' => $classData['grade_id'],
//         ]);
//     }

//     return redirect()->route('classrooms.index')->with('success', trans('My_Classes_trans.success_add'));
// }

//  public function store(Request $request)
// {


//     1. التحقق من صحة البيانات الواردة
    // $validated = $request->validate([
    //     'List_Classes'                       => 'required|array|min:1',
    //     'List_Classes.*.Name_class_en'       => [
    //         'required', 'string', 'max:255', 'distinct',
    //         // تضمن عدم تكرار الاسم الإنجليزي في جدول classrooms
    //          Rule::unique('classrooms', 'name_class->en'),
    //     ],
    //     'List_Classes.*.Name'                => [
    //         'required', 'string', 'max:255', 'distinct',
    //         // تضمن عدم تكرار الاسم العربي في جدول classrooms
    //          Rule::unique('classrooms', 'name_class->ar'),
    //     ],
    //     'List_Classes.*.grade_id'            => 'required|integer|exists:grades,id',
    // ]);

    // // 2. الحفظ داخل معاملة (transaction) لعمل Rollback عند أي خطأ
    // try {
    //     DB::transaction(function () use ($validated) {
    //         foreach ($validated['List_Classes'] as $class) {
    //             Classroom::create([
    //                 'name_class'     => [
    //                      'en' => $class['Name_class_en'],
    //                     'ar' => $class['Name'],
    //                 ],
    //                 'grade_id' => $class['grade_id'],
    //             ]);
    //         }
    //     });

    //     return redirect()
    //            ->route('classrooms.index')
    //            ->with('success', __('messages.saved_successfully'));

    // } catch (\Throwable $e) {               // \Throwable يشمل Exception و Error
    //     return redirect()->back()
    //            ->withErrors(['error' => $e->getMessage()])
    //            ->withInput();
//    }
public function store(Request $request)
{
    /** 1) التحقق */
    $validated = $request->validate([
        'List_Classes'                       => 'required|array|min:1',
        'List_Classes.*.Name_class_en'       => [
            'required', 'string', 'max:255', 'distinct',
            Rule::unique('classrooms', 'name_class->en'),   // فريد داخل key الإنجليزي
        ],
        'List_Classes.*.Name'                => [
            'required', 'string', 'max:255', 'distinct',
            Rule::unique('classrooms', 'name_class->ar'),   // فريد داخل key العربي
        ],
        'List_Classes.*.grade_id'            => 'required|integer|exists:grades,id',
    ]);

    /** 2) الحفظ داخل معاملة */
    DB::transaction(function () use ($validated) {
        foreach ($validated['List_Classes'] as $row) {
            Classroom::create([
                'name_class' => [                     // ➜ عمود JSON واحد
                    'en' => $row['Name_class_en'],
                    'ar' => $row['Name'],
                ],
                'grade_id'  => $row['grade_id'],
            ]);
        }
    });

    return redirect()
           ->route('classrooms.index')
           ->with('success', __('messages.saved_successfully'));
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
     $validated = $request->validate([
        'id'      => 'required|exists:classrooms,id',
        'Name'    => [
            'required', 'string', 'max:255',
            Rule::unique('classrooms', 'name_class->ar')->ignore($request->id),
        ],
        'Name_en' => [
            'required', 'string', 'max:255',
            Rule::unique('classrooms', 'name_class->en')->ignore($request->id),
        ],
    ]);

    try {
        $classroom = Classroom::findOrFail($request->id);
        $classroom->update([
            'name_class' => [
                'en' => $request->Name_en,
                'ar' => $request->Name,
            ],
            'grade_id' => $request->grade_id,
        ]);

        return redirect()->route('classrooms.index')->with('success', 'تم التعديل بنجاح');
    } catch (\Throwable $e) {
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         try {
        $class = Classroom::findOrFail($id);
        $class->delete();

        return redirect()->route('classrooms.index')->with('success', 'تم حذف الصف بنجاح');
    } catch (\Throwable $e) {
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }
    }


    //for search
public function Filter_Classes(Request $request)
{
    $Grades = Grade::all();
    $Search = Classroom::where('grade_id', $request->Grade_id)->get();

    return view('classrooms', [
        'Grades' => $Grades,
        'My_Classes' => $Search, // نفس اسم المتغير المستخدم في view عند عرض الصفوف
    ]);
}



}


