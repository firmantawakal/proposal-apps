<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Position;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $positions = Position::selectRaw('position.*, department.department_name')
                                ->leftJoin('department','department.id','=','position.department_id')
                                ->orderBy('department_name')->get();

        $users = User::where('status', 1)
                        ->selectRaw('users.*, position.position_name, department.department_name')
                        ->leftJoin('position','position.id','=','users.position_id')
                        ->leftJoin('department','department.id','=','position.department_id')
                        ->orderBy('users.created_at')->paginate(10);
        $users_archive = User::where('status', 0)->latest()->paginate(10);
        // dd($positions);
        return view('user.index', compact('users','users_archive','positions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'string|max:100',
            'email'       => 'required|unique:users|string|min:5',
            'position_id' => 'required',
            'password'    => 'required|min:5',
            'role'        => 'required',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }
        User::create($validated);

        return redirect()->route('user.index')
                        ->with('success','Data user berhasil ditambah');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('user.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request,User $user)
    {
        $validated = $request->validate([
            'name'        => 'string|max:100',
            'email'       => 'required|string|min:5|unique:users,email,'.$user->id,
            'role'        => 'required',
            'position_id' => 'required',
            // 'password'    => 'required|min:5',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('user.index')
                        ->with('success','Data user berhasil diubah');
    }

    public function updateProfile(Request $request,User $user)
    {
        $validated = $request->validate([
            'name'        => 'string|max:100',
            'no_telp'     => 'string',
            'alamat'      => 'string',
            'email'       => 'unique:users,email,'.$user->id,
        ]);

        if($request->input('password')!== null) {
            $validated['password'] = bcrypt($request->input('password'));
        }

        try {
            User::find($user->id)->update($validated);
            return redirect()->route('dashboard')
                             ->with('success','Data berhasil diubah');
        }  catch (\Exception $ex) {
            dd($ex);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('users')->where('id', $id)->update(['status' => 0]);

        return redirect()->route('user.index')
                        ->with('success','Data user berhasil di arsipkan');
    }

    public function unarchive($id)
    {
        DB::table('users')->where('id', $id)->update(['status' => 1]);

        return redirect()->route('user.index')
                        ->with('success','Data user berhasil di aktifkan');
    }
}
