<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Exports\StudentExport;
use App\Imports\StudentImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{

    
    public function index()
    {
        $student = Student::all();
        return view('index', compact('student'));
    }

   
    public function create()
    {
        return view('create');
    }

   
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|max:255',
            'phone' => 'required|numeric',
            'password' =>'required',
            'image_base64' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $input['name'] = $request->name;
        $input['email'] = $request->email;
        $input['phone'] = $request->phone;
        $input['password'] = $request->password;
        $input['photo'] = $this->storeBase64($request->image_base64);
        $student = Student::create($input);
        if ($student) {
            return redirect('/students')->with('success', 'Student has been saved!');
        }
        return redirect('/students/create')->with('success', 'Somthing went wrong. Try again');
    }

   
    public function storeBase64($imageBase64)
    {
        list($type, $imageBase64) = explode(';', $imageBase64);
        list(, $imageBase64)      = explode(',', $imageBase64);
        $imageBase64 = base64_decode($imageBase64);
        $imageName = time() . '.png';
        $path = public_path() . "/Images/" . $imageName;

        file_put_contents($path, $imageBase64);

        return $imageName;
    }

   
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('edit', compact('student'));
    }

    
    public function update(Request $request, $studentId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|max:255',
            'phone' => 'required|numeric',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $input['name'] = $request->name;
        $input['email'] = $request->email;
        $input['phone'] = $request->phone;
        $input['password'] = $request->password;
        if ($request->image_base64) {
            $input['photo'] = $this->storeBase64($request->image_base64);
        }
        $student = Student::where('student_id', $studentId)->update($input);
        if ($student) {
            return redirect('/students')->with('success', 'Student has been updated');
        }
        return redirect('/students')->with('success', 'Somthing went wrong. Try again');
    }

    
    public function destroy($studentId)
    {
        $student = Student::where('student_id', $studentId)->delete();
        if ($student) {
            return redirect('/students')->with('success', 'Student has been deleted');
        }
        return redirect('/students')->with('success', 'Somthing went wrong. Try again');
    }

   
    public function export()
    {
        return Excel::download(new StudentExport, 'students.xlsx');
    }

   
    public function import()
    {
        Excel::import(new StudentImport, request()->file('file'));
        return back();
    }
}
