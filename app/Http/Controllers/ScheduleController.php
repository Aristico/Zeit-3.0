<?php

namespace App\Http\Controllers;

use App\Entry;
use App\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Prophecy\Doubler\ClassPatch\MagicCallPatch;

/* In der Tabelle Entries werden die Einträge zu den geleisteten Arbeitszeiteen
 * eingetragen. Hier entsteht das Stundenkonto eines jeden Nutzers.
 *
 *
 *
 *
 * */


class ScheduleController extends Controller
{

    public function __construct()
    {

        $this->middleware(['auth'])->except(['create', 'store']);
        $this->middleware(['verified'])->except(['create', 'store', 'edit', 'update']);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (isset(Auth::User()->id)) {
            $id = Auth::User()->id;
        }

        if(count(Schedule::where('user_id', $id)->get()) > 0) {

            return redirect(route('schedule.edit'));

        } else {

            /*Lädt die Standard-Arbeitszeiten und üerzeut ein Array mit den enthaltenen Tagen.*/
            $schedule = Schedule::where('user_id', 1)->orderBy('day', 'asc')->get();
            /*Zeigt das mit den Standardzeiten Vorausgefüllte Formular.*/
            return view('user.schedule.create', compact('days', 'schedule', 'id'));

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*Sammelt den Input ein und setzt die zu Berechnende Arbeitszeit auf 0*/
        /*Der Input besteht aus einem Array mit sieben Feldern. Für jeden Tag eines.*/
        $input = $request->all();

        /* Geht das Array durch und legt für jeden Tag einen Datenbank-Eintrag an
         * Berechnet aus der Rückmeldung der Anlage die Arbeitszeit des Tages und Addiert die Stunden jedes Tages zu
         * Einer Wochenarbeitszeit.
         */
        foreach ($input['day'] as $day) {
            $schedule = Schedule::create($day);

        }

        /* Erzeugt die Erfolgsmeldung für den folgenden Screen
         * Die Session info "success" wird dafür benutzt um bestimmte Buttons ein/auszublenden.
         * */
        session()->flash('info_message', 'Ihr Zeitplan wurde gespeichert');
        session()->flash('success', 'true');

        /* Als nächstes wird das Edit-Fenster angezeigt.
         * Hier wird die berechnete Arbeitszeit ausgewiesen und kann ggf. nochmal geändert werden.
         * */
        return redirect(route('start'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        /* Zeigt das Bearbeiten Formular und füllt es mit den Werten aus der Datenbank aus.
         * */
        $schedule = Auth::User()->currentSchedule();
        return view('user.schedule.edit', compact('days', 'schedule'));

    //'user_id', '=', $id

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $id = Auth::User()->id;

        /* Beim ersten Aufruf und wenn nochmal geändert wurde, wird dieser Bereich abgespielt.
            * Stunden werden für die Berechnung auf 0 gesetzt
            * Der Request wird in ein array geschrieben
            * Das Gültig Ab datum wird auf den kommenden Montag gelegt, damit die aktuelle Woche nicht beeinflusst wird.
            * */

        $hours = 0;
        $date = new Carbon('Next Monday');
        $input = $request->day;

        /* Existiert schon eine Geänderte Version in der Zukunft, dann wird diese gelöscht.
            * */

        Auth::User()->schedules()->where('valid_from', '=', $date)->delete();


        /* Speichert die Daten in der Datenbank. Die Version wird um eins erhöht.
            * Das Gültig Ab Datum wird auch festgelegt.
            * */
        foreach ($input as $day) {

            $day['version'] += 1;
            $day['valid_from'] = $date->format('Y-m-d');
            $schedule = Schedule::create($day);

            $hours += $schedule->regularHours();

        }

             /* Erzeugt die Erfolgsmeldung für den folgenden Screen
              * Die Session info "success" wird dafür benutzt um bestimmte Buttons ein/auszublenden.
              * */

        session()->flash('info_message', 'Bitte prüfen Sie Ihre Wochenarbeitszeit. Ihre Angabe führen zu einer Wochenarbeitszeit von ' . $hours . ' Stunden.');
        session()->flash('success', 'true');

        /* Zeigt nochmal die Bearbeiten Seite an um sie zu bestätigen oder nochmal ändern zu können.
            * */

        return redirect(route('start'));





    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function destroy($id)
//    {
//        //
//    }
}
