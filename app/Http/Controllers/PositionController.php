<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Position;
use App\Models\Department;
use App\Models\Level;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $positions = Position::with('department')->get();
        $level = Level::all();
        $department = Department::all();
        return view('position', compact('positions','department','level'));
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
            'department_id' => 'required',
            'position_name' => 'required',
        ]);

        $input = $request->all();

        Position::create($input);

        return redirect()->route('position.index')
                        ->with('success','Data berhasil ditambah');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Position $position)
    {
        $val = $request->validate([
            'department_id' => 'required',
            'position_name' => 'required',
        ]);

        $position->update($val);

        return redirect()->route('position.index')
                        ->with('success','Data berhasil diubah');
    }

    public function editLevel(Request $request)
    {
        $input = $request->validate([
            'level_id' => 'required',
            'position_id' => 'required',
        ]);

        DB::table('level_position')->where('position_id',$request->position_id)->delete();

        foreach ($request->level_id as $key => $value) {
            $poslev = DB::table('level_position')
                    ->where('position_id', '=', $request->position_id)
                    ->where('level_id', '=', $value)
                    ->first();

            if ($poslev === null) {
                $dt = array(
                    'level_id' => $value,
                    'position_id' => $request->position_id,
                );
                DB::table('level_position')->insert($dt);
            }
        }

        return redirect()->route('position.index')
                        ->with('success','Data level berhasil ditambah');
    }
}
