<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserEditRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except(['create', 'store']);
    }
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

        return view('user.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {


        $input = $request->all();
        $input['password'] = bcrypt($request->password);
        $input['identifier'] = md5($request->email);


        try {

            $success = User::create($input);

        } catch (\Exception $e) { // I don't remember what exception it is specifically

            session()->flash('error_message', 'Bei der Benutzeranlage ist ein Fehler aufgetreten.');
            return redirect()->back();

        }

        session()->flash('success_message', 'Der Benutzer wurde angelegt.');

        if (Auth::loginUsingId($success->id)) {
            // Authentication passed...
            return redirect(route('schedule.create'));
            } else {

            session()->flash('error_message', 'Bei der Benutzeranlage ist ein Fehler aufgetreten. Der Anlageprozess konnte nicht fortgesetzt werden.');
            return redirect()->back();

        }

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
    public function edit()
    {

        $user = Auth::User();
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserEditRequest $request)
    {

        $user = Auth::User();

        if($request->password !== null) {

            $input = $request->all();
            $input['password'] = bcrypt($request->password);

        } else {

            $input = $request->except('password');

        }

        if ($input['command']=='save') {


            $user->update($input);
            return redirect(route('start'));

        } elseif ($input['command']=='delete') {

            return redirect(route('user.deleteForm'));

        } else {

            return redirect(route('start'));

        }

    }

    public function delete()
    {
        $user = Auth::User();
        return view('user.delete', compact('user'));
    }

    public function destroy(Request $request)
    {
        if ($request->command == 'yes') {

            $user = Auth::User();
            Auth::Logout();
            $user->delete();
            return redirect('/');

        } else {


        return redirect('start');

        }
    }
}
