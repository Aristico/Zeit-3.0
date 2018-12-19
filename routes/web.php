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

});

Auth::routes();

Route::get('/test', function () {
 dd(Hash::check('123', '$2y$10$jLTuVhJLSwg3d3XcTRoY2.B5kAWu0dy1Z7.TKFONGwnozfRFYwts2'));
});

Route::get('/user/settings/{id}/create', 'UserController@createSettings')->name('user.settings.create');
Route::put('/user/settings/{id}/update', 'UserController@updateSettings')->name('user.settings.update');
Route::resource('/user', 'UserController');
Route::get('/schedule/create/{id}', 'ScheduleController@create')->name('schedule.create');
Route::resource('/schedule', 'ScheduleController')->except(['create']);

Route::get('/entries/{id}/init', 'EntryController@initShow')->name('entries.init.show');
Route::post('/entries/{id}/init/set', 'EntryController@initSet')->name('entries.init.set');
Route::get('/entries/{id}/delete', 'EntryController@delete')->name('entries.delete');
Route::get('/entries/{user_id}/{date}/create', 'EntryController@create')->name('entries.create');
Route::get('/entries/{identifier}/enter', 'EntryController@enter')->name('entries.enter');
Route::get('/entries/{identifier}/leave', 'EntryController@leave')->name('entries.leave');

Route::resource('/entries', 'EntryController')->except('create');

Route::get('/start', function () {

    return view('user.home');

})->name('start');


