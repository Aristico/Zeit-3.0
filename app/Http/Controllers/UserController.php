<?php

namespace App\Http\Controllers;

use App\User;
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.users.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->all();
        $input['password'] = bcrypt($request->pasword);
        $input['identifier'] = md5($request->email);

        $success = User::create($input);

        session()->flash('success_message', 'Der Benutzer wurde angelegt.');

        return redirect()->action('ScheduleController@create', $success->id);
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
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);

        if($request->password !== null) {

            $input = $request->all();
            $input['password'] = bcrypt($request->password);

        } else {

            $input = $request->except('password');

        }

        $user->update($input);

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function createSettings($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.settings', compact('user'));

    }

    public function updateSettings(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $result = $user->update($request->all());
        redirect(route('start'));

    }

    public function destroy($id)
    {
        //
    }
}
