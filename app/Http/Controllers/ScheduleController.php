<?php

namespace App\Http\Controllers;

use App\Schedule;
use Illuminate\Http\Request;
use Prophecy\Doubler\ClassPatch\MagicCallPatch;

class ScheduleController extends Controller
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

        if(isset($_GET['id'])) {$userid = $_GET['id'];}

        $defaults = Schedule::where('user_id', 0)->get();
        $days = [1=>'Montag', 2=>'Dienstag', 3=>'Mittwoch', 4=>'Donnerstag', 5=>'Freitag', 6=>'Samstag', 7=>'Sonntag'];
        return view('admin.schedule.create', compact('days', 'defaults', 'userid'));
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


        foreach ($input['day'] as $day) {

            Schedule::create($day);

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
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
