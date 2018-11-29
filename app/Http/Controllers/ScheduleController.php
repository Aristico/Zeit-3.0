<?php

namespace App\Http\Controllers;

use App\Schedule;
use App\User;
use Carbon\Carbon;
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

        $hours = 0;

        foreach ($input['day'] as $day) {

            $schedule = Schedule::create($day);
            $hours += $schedule->regularHours();
        }

        session()->flash('success_message', 'Ber Zeitplan wurde angelegt. Ihre Wochenarbeitszeit beträgt ' . $hours . ' Stunden.');
        session()->flash('success', 'true');

        return redirect(route('schedule.edit', $schedule->user_id));

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
        $schedule = User::findOrFail($id)->currentSchedule();
        $days = [1=>'Montag', 2=>'Dienstag', 3=>'Mittwoch', 4=>'Donnerstag', 5=>'Freitag', 6=>'Samstag', 7=>'Sonntag'];
        return view('admin.schedule.edit', compact('days', 'schedule', 'id'));

    //'user_id', '=', $id

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

        if($request->ready == "true") {

            return redirect(route('start'));

        } else {

            $hours = 0;

            $date = new Carbon('Next Monday');
            $input = $request->day;

            if(Schedule::where('valid_from', '=', $date)->get()) {

                Schedule::where('valid_from', '=', $date)->delete();

            }

            foreach ($input as $day) {

                $day['version'] += 1;
                $day['valid_from'] = $date->format('Y-m-d');
                $schedule = Schedule::create($day);

                $hours += $schedule->regularHours();

            }

            session()->flash('success_message', 'Der Zeitplan wurde geändert. Ihre hinterlegte Wochenarbeitszeit beträgt ' . $hours . ' Stunden');
            session()->flash('success', 'true');

            return redirect(route('schedule.edit', $schedule->user_id));

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
        //
    }
}
