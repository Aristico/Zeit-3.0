<?php

namespace App\Http\Controllers;

use App\Entry;
use App\Http\Requests\EntryInitRequest;
use App\Http\Requests\EntryRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EntryController extends Controller
{

    public function __construct()
    {

        $this->middleware(['auth'])->except(['enter', 'leave']);
        $this->middleware(['verified'])->except(['enter', 'leave', 'initShow', 'initSet']);


    }

    public function setDateRangeForQuery ($year, $month, $fillUpWeeks = false) {

        /*Der Datumsbereich wird anhand der Parameter defniert. Vom ersten des Monats bis zum ...*/
        $range['from'] = new Carbon($year . '-'. $month . '-01');

        /*... letzten des Monats, maximal aber der heutige Tag*/
        $range['from']->format('Y-m') == date('Y-m') ? $range['to'] = new Carbon(date('Y-m-d')) : $range['to'] = new Carbon($range['from']->format('Y-m-t'));

        if ($fillUpWeeks == true) {

            $range['from']->previous(Carbon::MONDAY);
            $range['to']->next(Carbon::SUNDAY);

        }

        return $range;

    }


    public function getEntriesOfSelectedRange ($rangeOfQuery) {

        $entriesBase = Auth::user()->entries()
        ->orderBy('date', 'asc')
        ->where([['date', '>=', $rangeOfQuery['from']],
                 ['date', '<=', $rangeOfQuery['to']],
                 ['begin', '!=', null],
                 ['end', '!=', null]])
        ->get();

        if (count($entriesBase) == 0) {

            return null;

        }

        return $entriesBase;

    }

    public function fillUpEmptyEntrys ($entriesBase, $rangeOfQuery) {

        /*Jeder Tag im definierten Datumsbereich wird durchlaufen*/
        while ($rangeOfQuery['from'] <= $rangeOfQuery['to']) {

            /*Es wird geprüft ob am jeweiligen Datum KEIN Eintrag vorliegt*/
            if (count($entriesBase->where('date', $rangeOfQuery['from']->format('Y-m-d'))->all()) == 0) {

                $newEntry = new Entry;
                $newEntry->date = $rangeOfQuery['from']->format('Y-m-d');
                $newEntry->user_id = $entriesBase->first()->user_id;
                $newEntry->comment = 'no Entry';

                /* und in  die Collection geschrieben */
                $entriesBase->push($newEntry);

            }

            $rangeOfQuery['from']->addDay();

        };

        /*Die werte in der Tabelle werden Sortiert, damit die Liste in der Richtigen Reihenfolge erscheint*/
        return $entriesBase->sortBy('date');

    }

    public function index($year, $month)
    {

            $rangeOfQuery = $this->setDateRangeForQuery($year, $month);
            $entriesBase = $this->getEntriesOfSelectedRange($rangeOfQuery);

            if ($entriesBase != null) {

                $entries = $this->fillUpEmptyEntrys($entriesBase, $rangeOfQuery);
                $isEditable = $entries[0]->isEditable;

            } else {

                return view('user.entries.noEntries');

            }

            return view('user.entries.index', compact('entries', 'isEditable'));

    }

    public function createOvertimeStatement ($year, $month) {

        $rangeOfQuery = $this->setDateRangeForQuery($year, $month, true);
        $entriesBase = $this->getEntriesOfSelectedRange($rangeOfQuery);

        if ($entriesBase != null) {
            $entries = $this->fillUpEmptyEntrys($entriesBase, $rangeOfQuery);
        } else {
            return view('user.entries.noEntries');
        }

        return view('user.entries.index', compact('entries'));

    }

    public function balanceEndOfMonth() {

        $allEntries = Auth::user()->entries()->orderBy('date', 'asc')->where([['begin', '<>', null], ['end', '<>', null]])->get();

        if (count($allEntries) == 0) {

            return view('user.entries.noEntries');

        }

        $entries = collect();
        $exit = false;

        $month = Carbon::now();

        do {
            $month->subMonth();
            $singleEntry = $allEntries->where('date', '>=', $month->format('Y-m-01'))->where('date', '<', $month->addMonth()->format('Y-m-01'))->sortByDesc('date')->first();

            $month->subMonth();

            if($singleEntry != null) {

                $entries->push($singleEntry);

            } else {

                $exit = true;
            }

        } while ($exit == false);

        $monthes = ([1=>'Januar', 2=>'Februar', 3=>'März', 4=>'April',
                     5=>'Mai', 6=>'Juni', 7=>'Juli', 8=>'August',
                     9=>'September', 10=>'Oktober', 11=>'November', 12=>'Dezember']);

        return view('user.entries.balances', compact('entries', 'monthes'));

    }

    public function initShow ()
    {

        $id = Auth::User()->id;

        if(count(Entry::where('user_id', $id)->get()) > 0) {

            return redirect(route('start'));

        } else {

            return view('user.entries.init', compact('id'));
        }

    }

    public function initSet (EntryInitRequest $request)
    {
        $date = new Carbon(date('Y-01-01 00:00:00'));
        $input = $request->all('balance');
        $input['date'] = $date->subYear()->format('Y-m-d');
        $input['comment'] = 'Start';

        $user = Auth::User();
        $user->entries()->create($input);

        return redirect(route('start'));

    }

    public function enter($identifier) {

        Log::debug('EntryController - enter - Parameters: Identifier ' . $identifier);

        /*Ruft die aktuelle Zeit ab und den Benutzer, der zum identifier gehört*/
        $user = User::where('identifier', '=', $identifier)->firstOrFail();
        /*Prüft ob bereits ein Eintrag existiert*/
        if(count($user->entries()->where('date', date('Y-m-d'))->get())>0){
            Log::debug('EntryController - enter: Es Exitiert bereits ein Eintrag');
            return 'Es exitiert bereits ein Eintrag';
            //return view('user.entries.success', compact('msg'));
        } else {
            Log::debug('EntryController - enter: Es wird ein neuer Eintrag angelegt');
            /*Es werden Die Daten des Tages eingetragen */

                        $values = [
                'date'=>date('Y-m-d'), /*Datum von Heute*/
                'begin'=>date('H:i:s'), /*Aktuelle Uhrzeit*/
                'break'=>$user->scheduleByDate(date('Y-m-d'))['break'] == null ? 0 : $user->scheduleByDate(date('Y-m-d'))['break'], /*Die Pause aus dem Zeitplan*/
                'schedule_version'=>$user->scheduleByDate(date('Y-m-d'))['version'], /*Die aktuelle Version*/
                'regular_hours'=>$user->scheduleByDate(date('Y-m-d'))->regularHours() /*Die geplante Arbeitszeit*/
            ];

            Log::debug('EntryController - enter: Zu Speichernde Werte ' . print_r($values, true));

            $user->entries()->create($values);
            return 'Eintrag erfolgreich';
            //return view('user.entries.success', compact('msg'));
        }
    }

    public function leave($identifier) {

        /*Ruft die aktuelle Zeit ab und den Benutzer, der zum identifier gehört*/
        $user = User::where('identifier', '=', $identifier)->firstOrFail();

        Log::debug('EntryController - Leave - Parameter: Identifier ' . $identifier);

        /*Prüft ob bereits ein Eintrag existiert*/
        if(count($user->entries()->where('date', date('Y-m-d'))->get())>0){

            Log::debug('EntryController - Leave - Parameter: Es liegt bereits ein Eintrag vor');

            /*Ländt den letzten Eintrag für den Überstundensaldo*/
            $last_entry = $user->entries()->orderBy('date', 'DESC')->where('date','<', date('Y-m-d'))->first();

            log::debug('EntryController - Leave - Einträge - Last Entry ' . print_r($last_entry->toArray(), true));

            /*Lädt den aktuellen Eintrag*/
            $entry = $user->entries()->where('date', date('Y-m-d'))->firstOrFail();

            log::debug('EntryController - Leave - Einträge - Entry ' . print_r($last_entry->toArray(), true));

            /*Berechnet die aktuelle Arbeitszeit, die Überstunden und den Überstundensaldo*/
            $actualHours = $entry->calculateHours($entry->begin, date('H:i:s'), $entry->break);
            $overtime = $actualHours - $entry->regular_hours;
            $balance = $last_entry['balance']+$overtime;

            log::debug('EntryController - Leave - Calculated Values - actualHours' . $actualHours . 'overtime ' . $overtime . ' balance ' . $balance);

            $valuesToUpdate = [
                'end' =>date('H:i:s'),
                'actual_hours'=>$actualHours,
                'overtime'=>$overtime,
                'balance'=>$balance
            ];
            /*Schreibt die Daten in die Datenbank*/
            $entry->update($valuesToUpdate);

            Log::debug('EntryController - Leave: Der Eintrag wurde mit folgenden Werten aktualisert ' . print_r($valuesToUpdate, true));

            return "Der Eintrag wurde aktualisiert.";
            //return view('user.entries.success', compact('msg'));
        } else {
            Log::debug('EntryController - Leave - Parameter: Es liegt noch kein Eintrag vor. Er wird angelegt.');

            $valuesToCreate = [
                'date'=>date('Y-m-d'), /*Datum von Heute*/
                'begin'=>date('H:i:s'), /*Aktuelle Uhrzeit*/
                'end'=>date('H:i:s'), /*Aktuelle Uhrzeit*/
                'break'=>$user->scheduleByDate(date('Y-m-d'))['break'] == null ? 0 : $user->scheduleByDate(date('Y-m-d'))['break'], /*Die Pause aus dem Zeitplan*/
                'schedule_version'=>$user->scheduleByDate(date('Y-m-d'))['version'], /*Die aktuelle Version*/
                'regular_hours'=>$user->scheduleByDate(date('Y-m-d'))->regularHours() /*Die geplante Arbeitszeit*/
            ];
            /*Es werden Die Daten des Tages eingetragen */
            $user->entries()->create($valuesToCreate);

            Log::debug('EntryController - Leave: Der Eintrag wurde mit folgenden Werten angelegt ' . print_r($valuesToCreate, true));

            return "Es exitierte noch kein Eintrag und es wurde einer angelegt";
            //return view('user.entries.success', compact('msg'));
        }
    }

    public function showCorrection ($id) {

            $entry = Entry::findOrFail($id);

            return view('user.entries.correction', compact('entry'));

    }

    public function setCorrection (Request $request, $id) {

        $request->validate([
            'correction' => 'min:0'
        ]);

        $input = $request->all();
        $entry = Entry::findOrFail($id);

        $entry->update(['correction' => $input['correction']]);

        $this->recalculateBalance($entry);

        return redirect(route('entries.index.balances'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($date)
    {
        $schedule = Auth::User()->scheduleByDate($date);
        return view('user.entries.create', compact('date', 'schedule'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EntryRequest $request)
    {
        $input = $request->all();

        $entry = Entry::create($input);

        $actual_hours = $entry->calculateHours($request->begin, $request->end, $request->break);
        $overtime = $actual_hours - $entry->regular_hours;

        $entry->update([
            'actual_hours'=>$actual_hours,
            'overtime'=>$overtime
        ]);

        $this->recalculateBalance($entry);

        /*$lastEntry = Entry::where([['user_id', $entry->user_id], ['date', '<', $entry->date]])->orderBy('date', 'desc')->first();

        $entries = Entry::where('date', '>', $lastEntry->date)->where('user_id', $entry->user_id)->orderBy('date', 'asc')->get();

        $balance = $lastEntry->balance;

        foreach ($entries as $row) {
            $balance = $balance + $row->overtime;
            Entry::findOrFail($row->id)->update(['balance'=>$balance]);
        } */

        return redirect(route('entries.index.month', ['month'=>$input['month'], 'year'=>$input['year']]));

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
        return view('user.entries.edit', compact('entry'));
    }

    public function recalculateBalance ($entry) {

        $lastEntry = Entry::where([['user_id', $entry->user_id], ['date', '<', $entry->date]])->orderBy('date', 'desc')->first();

        $entries = Entry::where('date', '>', $lastEntry->date)->where('user_id', $entry->user_id)->orderBy('date', 'asc')->get();

        $balance = $lastEntry->balance;

        $correction = 0;
        foreach ($entries as $row) {
            $balance = $balance + $row->overtime - $correction;
            Entry::findOrFail($row->id)->update(['balance'=>$balance]);
            $correction = $row->correction;
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EntryRequest $request, $id)
    {
        $input = $request->all();

        if ($input['command']=='save') {

            $entry = Entry::findOrFail($id);

            /*Berechnet die werte auf Basis der eingegebenen Daten*/
            $input['actual_hours'] = $entry->calculateHours($input['begin'], $input['end'], $input['break']);
            $input['overtime'] = $input['actual_hours'] - $entry->regular_hours;
            $entry->update($input);

            $this->recalculateBalance($entry);

        return redirect(route('entries.index.month', ['month'=>$input['month'], 'year'=>$input['year']]));

        }  elseif ($input['command'] == 'delete') {

            return redirect(route('entries.delete', $id));

        } else {

            return redirect(route('entries.index.month', ['month'=>$input['month'], 'year'=>$input['year']]));

        }

    }

    public function delete($id) {

        $entry = Entry::findOrFail($id);
        return view('user.entries.delete', compact('entry'));

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

        if($input['command'] == 'yes') {

            $entry = Entry::findOrFail($id);

            $entry->delete();

            $this->recalculateBalance($entry);

            return redirect(route('entries.index.month', ['month'=>$input['month'], 'year'=>$input['year']]));

        } else {

            return redirect(route('entries.index.month', ['month'=>$input['month'], 'year'=>$input['year']]));

        }

    }
}
