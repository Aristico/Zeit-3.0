<?php

namespace App\Http\Controllers;

use App\Entry;
use App\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntryController extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (Auth::check()) {

            $entriesBase = Auth::user()->entries()->orderBy('date', 'asc')->where([['begin','<>', null], ['end','<>',null]])->get();

            $date = new Carbon($entriesBase->first()->dateCarbon()->format('Y-m-01'));

            while ($date->format('Y-m-d') <= date('Y-m-d')) {

                if(count($entriesBase->where('date', $date->format('Y-m-d'))->all())==0) {

                    $newEntry = new Entry;
                    $newEntry->date = $date->format('Y-m-d');
                    $newEntry->user_id = $entriesBase->first()->user_id;
                    $newEntry->comment = 'no Entry';
                    $entriesBase->push($newEntry);


                }

                $date->addDay();

            }

            $entries = $entriesBase->sortBy('date');
            return view('admin.entrys.index', compact('entries'));

        } else {

            return "Es ist kein User angemeldet!";

        }

    }

    public function initShow ($id)
    {
        return view('admin.entrys.init', compact('id'));

    }

    public function initSet (Request $request, $id)
    {
        $date = new Carbon(date('Y-01-01 00:00:00'));
        $input = $request->all('balance');
        $input['date'] = $date->format('Y-m-d');
        $input['comment'] = 'Start';

        $user = User::findOrFax^il($id);
        $user->entries()->create($input);

        return redirect(route('start'));

    }

    public function enter($identifier) {


        /*Ruft die aktuelle Zeit ab und den Benutzer, der zum identifier gehört*/
        $user = User::where('identifier', '=', $identifier)->firstOrFail();
        /*Prüft ob bereits ein Eintrag existiert*/
        if(count($user->entries()->where('date', date('Y-m-d'))->get())>0){
            return 'Es exitiert bereits ein Eintrag';
        } else {
            /*Es werden Die Daten des Tages eingetragen */
            $user->entries()->create([
                'date'=>date('Y-m-d'), /*Datum von Heute*/
                'begin'=>date('H:i:s'), /*Aktuelle Uhrzeit*/
                'break'=>$user->currentScheduleToday()['break'] == null ? 0 : $user->currentScheduleToday()['break'], /*Die Pause aus dem Zeitplan*/
                'schedule_version'=>$user->currentScheduleToday()['version'], /*Die aktuelle Version*/
                'regular_hours'=>$user->currentScheduleToday()->regularHours() /*Die geplante Arbeitszeit*/
            ]);
            return 'Es wurde ein Eintrag angelegt';
        }
    }

    public function leave($identifier) {

        /*Ruft die aktuelle Zeit ab und den Benutzer, der zum identifier gehört*/
        $user = User::where('identifier', '=', $identifier)->firstOrFail();

        /*Prüft ob bereits ein Eintrag existiert*/
        if(count($user->entries()->where('date', date('Y-m-d'))->get())>0){
            /*Ländt den letzten Eintrag für den Überstundensaldo*/
            $last_entry = $user->entries()->orderBy('date', 'DESC')->where('date','<', date('Y-m-d'))->first();

            /*Lädt den aktuellen Eintrag*/
            $entry = $user->entries()->where('date', date('Y-m-d'))->firstOrFail();

            /*Berechnet die aktuelle Arbeitszeit, die Überstunden und den Überstundensaldo*/
            $actualHours = $entry->calculateHours($entry->begin, date('H:i:s'), $entry->break);
            $overtime = $actualHours - $entry->regular_hours;
            $balance = $last_entry['balance']+$overtime;

            /*Schreibt die Daten in die Datenbank*/
            $entry->update([
                'end' =>date('H:i:s'),
                'actual_hours'=>$actualHours,
                'overtime'=>$overtime,
                'balance'=>$balance
            ]);
            return "Der Eintrag wurde aktualisiert.";
        } else {
            /*Es werden Die Daten des Tages eingetragen */
            $user->entries()->create([
                'date'=>date('Y-m-d'), /*Datum von Heute*/
                'begin'=>date('H:i:s'), /*Aktuelle Uhrzeit*/
                'end'=>date('H:i:s'), /*Aktuelle Uhrzeit*/
                'break'=>$user->currentScheduleToday()['break'] == null ? 0 : $user->currentScheduleToday()['break'], /*Die Pause aus dem Zeitplan*/
                'schedule_version'=>$user->currentScheduleToday()['version'], /*Die aktuelle Version*/
                'regular_hours'=>$user->currentScheduleToday()->regularHours() /*Die geplante Arbeitszeit*/
            ]);
            return 'Es exitierte noch kein Eintrag und es wurde einer angelegt';
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($user_id, $date)
    {
        $schedule = User::findOrFail($user_id)->scheduleByDate($date);
        return view('admin.entrys.create', compact('date', 'schedule'));
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

        $entry = Entry::create($input);

        $actual_hours = $entry->calculateHours($request->begin, $request->end, $request->break);
        $overtime = $actual_hours - $entry->regular_hours;

        $entry->update([
            'actual_hours'=>$actual_hours,
            'overtime'=>$overtime
        ]);

        $lastEntry = Entry::where([['user_id', $entry->user_id], ['date', '<', $entry->date]])->orderBy('date', 'desc')->first();

        $entries = Entry::where('date', '>', $lastEntry->date)->where('user_id', $entry->user_id)->orderBy('date', 'asc')->get();

        $balance = $lastEntry->balance;

        foreach ($entries as $row) {
            $balance = $balance + $row->overtime;
            Entry::findOrFail($row->id)->update(['balance'=>$balance]);
        }

        return redirect(route('entries.index'));


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
        $entry = Entry::findOrFail($id);
        return view('admin.entrys.edit', compact('entry'));
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
        $input = $request->all();

        if ($input['command']=='save') {

            $entry = Entry::findOrFail($id);

            /*Berechnet die werte auf Basis der eingegebenen Daten*/
            $input['actual_hours'] = $entry->calculateHours($input['begin'], $input['end'], $input['break']);
            $input['overtime'] = $input['actual_hours'] - $entry->regular_hours;
            $entry->update($input);

            $lastEntry = Entry::where([['user_id', $entry->user_id], ['date', '<', $entry->date]])->orderBy('date', 'desc')->first();

            $entries = Entry::where('date', '>', $lastEntry->date)->where('user_id', $entry->user_id)->orderBy('date', 'asc')->get();

            $balance = $lastEntry->balance;

            foreach ($entries as $row) {
                $balance = $balance + $row->overtime;
                Entry::findOrFail($row->id)->update(['balance'=>$balance]);
            }

        return redirect(route('entries.index'));

        }  elseif ($input['command'] == 'delete') {

            return redirect(route('entries.delete', $id));

        } else {

            return redirect(route('entries.index'));

        }

    }

    public function delete($id) {

        $entry = Entry::findOrFail($id);
        return view('admin.entrys.delete', compact('entry'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $input = $request->all();

        if($input['command'] = 'yes') {

            $entry = Entry::findOrFail($id);

            $lastEntry = Entry::where([['user_id', $entry->user_id], ['date', '<', $entry->date]])->orderBy('date', 'desc')->first();

            $entry->delete();

            $balance = $lastEntry->balance;

            $entries = Entry::where([['date', '>', $lastEntry->date], ['user_id', $lastEntry->user_id]])->orderBy('date', 'asc')->get();

            foreach ($entries as $row) {
                $balance = $balance + $row->overtime;
                Entry::findOrFail($row->id)->update(['balance' => $balance]);
            }

            return redirect(route('entries.index'));

        } else {

            return redirect(route('entries.index'));

        }

    }
}
