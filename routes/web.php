<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Entry;
use App\Role;
use App\Schedule;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;



route::get('/init', function () {

    Role::create(['name'=>'user']);
    Role::create(['name'=>'administrator']);

    User::create([
        'firstname'=>'John',
        'name'=>'Doe',
        'email'=>'john@doe.net',
        'password'=>bcrypt('K)/Llo7u'),
        'identifier'=>md5('john@doe.net') ]);

    Schedule::create(['user_id'=>1, 'day'=>1, 'begin'=>'08:00', 'end'=>'18:00:00', 'break'=>60]);
    Schedule::create(['user_id'=>1, 'day'=>2, 'begin'=>'08:00:00', 'end'=>'16:00:00', 'break'=>60]);
    Schedule::create(['user_id'=>1, 'day'=>3, 'begin'=>'08:00:00', 'end'=>'16:00:00', 'break'=>60]);
    Schedule::create(['user_id'=>1, 'day'=>4, 'begin'=>'08:00:00', 'end'=>'18:00:00', 'break'=>60]);
    Schedule::create(['user_id'=>1, 'day'=>5, 'begin'=>'08:00:00', 'end'=>'16:00:00', 'break'=>60]);
    Schedule::create(['user_id'=>1, 'day'=>6]);
    Schedule::create(['user_id'=>1, 'day'=>7]);

});

route::get('/init/example', function () {

    User::create([
        'firstname'=>'Christian',
        'name'=>'Ohning',
        'email'=>'mail@ohning.net',
        'password'=>bcrypt('K)/Llo7u'),
        'identifier'=>md5('mail@ohning.net') ]);

    Schedule::create(['user_id'=>2, 'day'=>1, 'begin'=>'08:00', 'end'=>'18:00:00', 'break'=>60]);
    Schedule::create(['user_id'=>2, 'day'=>2, 'begin'=>'08:00:00', 'end'=>'16:00:00', 'break'=>60]);
    Schedule::create(['user_id'=>2, 'day'=>3, 'begin'=>'08:00:00', 'end'=>'16:00:00', 'break'=>60]);
    Schedule::create(['user_id'=>2, 'day'=>4, 'begin'=>'08:00:00', 'end'=>'18:00:00', 'break'=>60]);
    Schedule::create(['user_id'=>2, 'day'=>5, 'begin'=>'08:00:00', 'end'=>'16:00:00', 'break'=>60]);
    Schedule::create(['user_id'=>2, 'day'=>6]);
    Schedule::create(['user_id'=>2, 'day'=>7]);

    Entry::create(['user_id'=>2, 'date'=>'2018-01-01', 'break'=>0, 'regular_hours'=>0,
                   'balance'=>0, 'schedule_version'=>1, 'comment'=>'Start']);


    $dateFrom = new Carbon('2018-02-01');

    while ($dateFrom->format('Y-m-d') <= date('Y-m-d')) {


        $schedule = User::findOrFail(2)->scheduleByDate($dateFrom);
        $input = [];
        $begin = new Carbon($schedule->begin);
        $end = new Carbon($schedule->end);
        $input['user_id'] = 2;
        $input['date'] = $dateFrom->format('Y-m-d');
        $input['begin'] = $begin->addMinutes(rand(0, 60))->format('H:i:s');
        $input['end'] = $end->addMinutes(rand(0, 60))->format('H:i:s');
        $input['break'] = $schedule->break;
        $entry = new Entry();
        $input['regular_hours'] = $entry->calculateHours($schedule->begin, $schedule->end, $schedule->break);
        $input['actual_hours'] = $entry->calculateHours($input['begin'], $input['end'], $input['break']);
        $input['overtime'] = $input['actual_hours'] - $input['regular_hours'];
        $input['version'] = $schedule->version;

        $entry->create($input);

        if ($dateFrom->format('N') < 5) {
            $dateFrom->addDay();
        } else {
            $dateFrom->addDays(3);
        }

    }
});


Auth::routes(['verify'=>true]);

Route::get('/test', function () {

    $password = 'hallo';
    $hashed = bcrypt($password);

    //dd(Hash::check($password, $hashed));

    dd($hashed);
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/', function () {

        return view('user.home');

    })->name('start');


    Route::get('/start', function () {

        return view('user.home');

    })->name('start');

    Route::get('/user/edit', 'UserController@edit')->name('user.edit');
    Route::put('/user', 'UserController@update')->name('user.update');
    Route::get('/user/delete', 'UserController@delete')->name('user.deleteForm');
    Route::delete('/user', 'UserController@destroy')->name('user.destroy');

});



Route::get('/entries/{id}/delete', 'EntryController@delete')->name('entries.delete');
Route::get('/entries/{date}/create', 'EntryController@create')->name('entries.create');

Route::get('/entries/index/{year}/{month}', 'EntryController@index')->name('entries.index.month');
Route::get('/entries/index/{year}/{month}/statement', 'EntryController@createOvertimeStatement')->name('entries.index.month.statement');

Route::get('/entries/index/balances', 'EntryController@balanceEndOfMonth')->name('entries.index.balances');

Route::resource('/entries', 'EntryController')->except('create');


Route::get('/entries/init', 'EntryController@initShow')->name('entries.init.show');
Route::post('/entries/init/set', 'EntryController@initSet')->name('entries.init.set');

Route::put('/schedule/update', 'ScheduleController@update')->name('schedule.update');
Route::get('/schedule/edit', 'ScheduleController@edit')->name('schedule.edit');
Route::resource('/schedule', 'ScheduleController')->except(['update', 'edit']);


Route::resource('/user', 'UserController')->except('edit', 'update', 'delete', 'destroy');

Route::get('/entries/{identifier}/enter', 'EntryController@enter')->name('entries.enter');
Route::get('/entries/{identifier}/leave', 'EntryController@leave')->name('entries.leave');

Route::get('/start', function () {

    return view('user.home');

})->name('start');

Route::get('/start', function () {

    return view('user.home');

})->name('start');


