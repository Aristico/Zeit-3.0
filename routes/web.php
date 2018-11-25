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

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/test', function () {
    $day = 1;
    $date = new carbon($day . ' Day of Week');
    return $date;
});

Route::resource('/user', 'UserController');
Route::resource('/schedule', 'ScheduleController');

Route::get('/enter/{identifier}', 'EntryController@enter');