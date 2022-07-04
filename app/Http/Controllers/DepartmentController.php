<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Department;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::all();
        return view('department', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'department_name' => 'required',
            'nomor' => 'required',
            'struktur' => 'required',
        ]);

        $input = $request->all();


        Department::create($input);

        return redirect()->route('department.index')
                        ->with('success','Data berhasil ditambah');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $val = $request->validate([
            'department_name' => 'required',
            'nomor' => 'required',
            'struktur' => 'required',
        ]);

        $department->update($val);

        return redirect()->route('department.index')
                        ->with('success','Data berhasil diubah');
    }
}
