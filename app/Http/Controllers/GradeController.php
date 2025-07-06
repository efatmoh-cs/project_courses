<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Support\Facades\DB;
use App\Models\Grade;
use Toastr;

use App\Http\Requests\StoreGrades;
use Illuminate\Http\Request;

class GradeController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()

  {
// If you want the grades sorted (e.g., by name or ID):
//     $Grades = Grade::orderBy('id', 'asc')->get();

     $Grades = Grade::all();
    return view('pages.Grades.Grades',compact('Grades'));

  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {

  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
//   public function store(StoreGrades $request)
//   {
//  Grade::create([
//         'name' => $request->name,
//         'notes' => $request->notes,
//     ]);

//     return redirect()->route('grades.index')->with('success', 'Grade created successfully.');
//   }
public function store(Request $request)
{
    $request->validate([
        'name.en'=> 'required|string|max:255|unique:grades,name->en',
        'name.ar'=> 'required|string|max:255|unique:grades,name->ar',
        'notes.en'=> 'nullable|string|max:255',
        'notes.ar'=> 'nullable|string|max:255',
    ],
    [
        'name.en.unique' => trans('Grades_trans.Name.unique'),
        'name.ar.unique' => trans('Grades_trans.exists'),
        //  'name.en.unique'   => trans('Grades_trans.exists'),
    ]);

    Grade::create([
        'name' => $request->input('name'),   // ['en' => ..., 'ar' => ...]
        'notes' => $request->input('notes'), // ['en' => ..., 'ar' => ...]
    ]);

    return redirect()->route('grades.index')->with('success', 'Grade added successfully.');
}

// public function store(Request $request)
// {
//     $request->validate([
//         'name.en'   => 'required|string|max:255|unique:grades,name->en',
//         'name.ar'   => 'required|string|max:255|unique:grades,name->ar',
//         'notes.en'  => 'nullable|string|max:255',
//         'notes.ar'  => 'nullable|string|max:255',
//     ],
//     // [
//     //     'name.en.unique' => 'The English name already exists.',
//     //     'name.ar.unique' => 'The Arabic name already exists.',


//     // ]);
//       // Custom uniqueness check
//     $duplicateEn = Grade::where('name->en', $request->input('name.en'))->exists();
//     $duplicateAr = Grade::where('name->ar', $request->input('name.ar'))->exists();

//     if ($duplicateEn) {
//         return back()->withErrors(['name.en' => 'The English name already exists.'])->withInput();
//     }

//     if ($duplicateAr) {
//         return back()->withErrors(['name.ar' => 'The Arabic name already exists.'])->withInput();
//     }

//     Grade::create([
//         'name'  => [
//             'en' => $request->input('name.en'),
//             'ar' => $request->input('name.ar'),
//         ],
//         'notes' => [
//             'en' => $request->input('notes.en'),
//             'ar' => $request->input('notes.ar'),
//         ],
//     ]);

//     return redirect()->route('grades.index')->with('success', 'Grade added successfully.');
// }

// public function store(Request $request)
// {
//     // Validate the basic structure
//     $request->validate([
//         'name.en'   => 'required|string|max:255',
//         'name.ar'   => 'required|string|max:255',
//         'notes.en'  => 'nullable|string|max:255',
//         'notes.ar'  => 'nullable|string|max:255',
//     ]);

//     // Manual uniqueness check for Spatie translatable fields
//     $duplicateEn = Grade::where('name->en', $request->input('name.en'))->exists();
//     $duplicateAr = Grade::where('name->ar', $request->input('name.ar'))->exists();

//     if ($duplicateEn || $duplicateAr) {
//         $errors = [];
//         if ($duplicateEn) $errors['name.en'] = 'The English name already exists.';
//         if ($duplicateAr) $errors['name.ar'] = 'The Arabic name already exists.';
//         return back()->withErrors($errors)->withInput();
//     }

//     // Create the grade
//     Grade::create([
//         'name'  => [
//             'en' => $request->input('name.en'),
//             'ar' => $request->input('name.ar'),
//         ],
//         'notes' => [
//             'en' => $request->input('notes.en'),
//             'ar' => $request->input('notes.ar'),
//         ],
//     ]);

//     return redirect()->route('grades.index')->with('success', 'Grade added successfully.');
// }




  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {

  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {

  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
//   public function update($id)
//   {

//   }
// public function update(Request $request, string $id)
//     {

//         $data = $request->validate([
//             'name'=> 'required|string|max:255',
//             'notes'=> 'required|string|max:255',


//         ]);

//         Grade::where('id', $id)->update($data);
//         return ( 'Grade added successfully.');

//     }
public function update(Request $request, string $id)
{
    $request->validate([
        'name' => 'required|string|max:255',     // Arabic
        'name_en' => 'required|string|max:255',  // English
        'notes' => 'nullable|string|max:255',
    ]);

    $grade = Grade::findOrFail($id);

    $grade->update([
        'name' => [
            'ar' => $request->name,
            'en' => $request->name_en,
        ],
        'notes' => $request->notes,
    ]);

    return redirect()->route('grades.index')->with('success', 'Grade updated successfully.');
}


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
//  public function destroy(string $id)
//     {

//        Grade::where('id', $id)->delete();

//         return redirect()->route('grades.index');
// }

 public function destroy(Request $request)
  {
         try {
        // هل توجد صفوف مرتبطة بهذه المرحلة؟
        $hasClasses = Classroom::where('grade_id', $request->id)->exists();

        if ($hasClasses) {
            // أضف رسالة الخطأ المترجمة إلى الـ session
            return redirect()
                ->route('grades.index')
                ->with('error', trans('Grades_trans.delete_Grade_Error'));
        }

        // لا توجد صفوف، احذف المرحلة داخل معاملة
        DB::transaction(function () use ($request) {
            Grade::findOrFail($request->id)->delete();
        });

        return redirect()
            ->route('grades.index')
            ->with('success', __('Grades_trans.delete_Grade_Success'));

    } catch (\Throwable $e) {
        // أي خطأ غير متوقَّع
        return redirect()
            ->route('grades.index')
            ->withErrors(['error' => $e->getMessage()]);
    }
    
}
}

?>
