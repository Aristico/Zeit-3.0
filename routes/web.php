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

use App\Role;
use App\Schedule;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {
    return view('welcome');
});

route::get('/init', function () {

    Role::create(['name'=>'user']);
    Role::create(['name'=>'administrator']);

    User::create([
        'firstname'=>'Christian',
        'name'=>'Ohning',
        'email'=>'mail@ohning.net',
        'password'=>bcrypt('K)/Llo7u'),
        'identifier'=>md5('mail@ohning.net') ]);

    Schedule::create(['user_id'=>0, 'day'=>1, 'begin'=>'08:00', 'end'=>'18:00:00', 'break'=>60]);
    Schedule::create(['user_id'=>0, 'day'=>2, 'begin'=>'08:00:00', 'end'=>'16:00:00', 'break'=>60]);
    Schedule::create(['user_id'=>0, 'day'=>3, 'begin'=>'08:00:00', 'end'=>'16:00:00', 'break'=>60]);
    Schedule::create(['user_id'=>0, 'day'=>4, 'begin'=>'08:00:00', 'end'=>'18:00:00', 'break'=>60]);
    Schedule::create(['user_id'=>0, 'day'=>5, 'begin'=>'08:00:00', 'end'=>'16:00:00', 'break'=>60]);
    Schedule::create(['user_id'=>0, 'day'=>6]);
    Schedule::create(['user_id'=>0, 'day'=>7]);

    Schedule::create(['user_id'=>1, 'day'=>1, 'begin'=>'08:00', 'end'=>'18:00:00', 'break'=>60]);
    Schedule::create(['user_id'=>1, 'day'=>2, 'begin'=>'08:00:00', 'end'=>'16:00:00', 'break'=>60]);
    Schedule::create(['user_id'=>1, 'day'=>3, 'begin'=>'08:00:00', 'end'=>'16:00:00', 'break'=>60]);
    Schedule::create(['user_id'=>1, 'day'=>4, 'begin'=>'08:00:00', 'end'=>'18:00:00', 'break'=>60]);
    Schedule::create(['user_id'=>1, 'day'=>5, 'begin'=>'08:00:00', 'end'=>'16:00:00', 'break'=>60]);
    Schedule::create(['user_id'=>1, 'day'=>6]);
    Schedule::create(['user_id'=>1, 'day'=>7]);

    Entry::create(['user_id'=>1, 'date'=>'2018-01-01', 'break'=>0, 'regular_hours'=>0,
                   'balance'=>0, 'schedule_version'=>1, 'comment'=>'Start']);

});

Auth::routes();


Route::get('/test', function () {

    $password = 'hallo';
    $hashed = bcrypt($password);

    //dd(Hash::check($password, $hashed));

    dd($hashed);
});


Route::resource('/user', 'UserController');

Route::middleware(['auth'])->group(function () {

    Route::put('/schedule/update', 'ScheduleController@update')->name('schedule.update');
    Route::get('/schedule/edit', 'ScheduleController@edit')->name('schedule.edit');
    Route::resource('/schedule', 'ScheduleController')->except(['update', 'edit']);

    Route::get('/entries/init', 'EntryController@initShow')->name('entries.init.show');
    Route::post('/entries/init/set', 'EntryController@initSet')->name('entries.init.set');
    Route::get('/entries/{id}/delete', 'EntryController@delete')->name('entries.delete');
    Route::get('/entries/{date}/create', 'EntryController@create')->name('entries.create');

    Route::resource('/entries', 'EntryController')->except('create');


});

Route::get('/entries/{identifier}/enter', 'EntryController@enter')->name('entries.enter');
Route::get('/entries/{identifier}/leave', 'EntryController@leave')->name('entries.leave');

Route::get('/start', function () {

    return view('user.home');

})->name('start');


