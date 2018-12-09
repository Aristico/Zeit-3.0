<?php

namespace App\Http\Controllers;

use App\User;
use DateTime;
use Illuminate\Http\Request;

class EntryController extends Controller
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

    public function initShow ($id)
    {
        return view('admin.entrys.init', compact('id'));

    }

    public function initSet (Request $request, $id)
    {
        $date = new DateTime('now');
        $input = $request->all('balance');
        $input['date'] = $date->modify('-1 day')->format('Y-m-d');
        $input['comment'] = 'Start';

        $user = User::findOrFail($id);
        $user->entries()->create($input);

        return redirect(route('start'));

    }

    public function enter($identifier) {

        /*Ruft die aktuelle Zeit ab und den Benutzer, der zum identifier gehört*/
        $now = new DateTime('now');
        $user = User::where('identifier', '=', $identifier)->firstOrFail();

        /*Prüft ob bereits ein Eintrag existiert*/
        if(count($user->entries()->where('date', $now->format('Y-m-d'))->get())>0){
            return 'Es exitiert bereits ein Eintrag';
        } else {
            /*Es werden Die Daten des Tages eingetragen */
            $user->entries()->create([
                'date'=>$now->format('Y-m-d'), /*Datum von Heute*/
                'begin'=>$now->format('H:i:s'), /*Aktuelle Uhrzeit*/
                'break'=>$user->currentScheduleToday()['break'] == null ? 0 : $user->currentScheduleToday()['break'], /*Die Pause aus dem Zeitplan*/
                'schedule_version'=>$user->currentScheduleToday()['version'], /*Die aktuelle Version*/
                'regular_hours'=>$user->currentScheduleToday()->regularHours() /*Die geplante Arbeitszeit*/
            ]);
            return 'Es wurde ein Eintrag angelegt';
        }
    }

    public function leave($identifier) {

        /*Ruft die aktuelle Zeit ab und den Benutzer, der zum identifier gehört*/
        $now = new DateTime('now');
        $user = User::where('identifier', '=', $identifier)->firstOrFail();

        /*Prüft ob bereits ein Eintrag existiert*/
        if(count($user->entries()->where('date', $now->format('Y-m-d'))->get())>0){
            /*Ländt den letzten Eintrag für den Überstundensaldo*/
            $last_entry = $user->entries()->orderBy('date', 'DESC')->where('date','<', $now->format('Y-m-d'))->first();

            /*Lädt den aktuellen Eintrag*/
            $entry = $user->entries()->where('date', $now->format('Y-m-d'))->firstOrFail();

            /*Berechnet die aktuelle Arbeitszeit, die Überstunden und den Überstundensaldo*/
            $actualHours = $entry->calculateHours($entry->begin, $now->format('H:i:s'), $entry->break);
            $overtime = $actualHours - $entry->regular_hours;
            $balance = $last_entry['balance']+$overtime;

            /*Schreibt die Daten in die Datenbank*/
            $entry->update([
                'end' => $now->format('H:i:s'),
                'actual_hours'=>$actualHours,
                'overtime'=>$overtime,
                'balance'=>$balance
            ]);
        } else {
            /*Es werden Die Daten des Tages eingetragen */
            $user->entries()->create([
                'date'=>$now->format('Y-m-d'), /*Datum von Heute*/
                'begin'=>$now->format('H:i:s'), /*Aktuelle Uhrzeit*/
                'end'=>$now->format('H:i:s'), /*Aktuelle Uhrzeit*/
                'break'=>$user->currentScheduleToday()['break'] == null ? 0 : $user->currentScheduleToday()['break'], /*Die Pause aus dem Zeitplan*/
                'schedule_version'=>$user->currentScheduleToday()['version'], /*Die aktuelle Version*/
                'regular_hours'=>$user->currentScheduleToday()->regularHours() /*Die geplante Arbeitszeit*/
            ]);
            return 'Es exitierte noch kein eintrag und es wurde einer angelegt';
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
