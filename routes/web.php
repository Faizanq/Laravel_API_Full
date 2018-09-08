<?php

use Illuminate\Support\Facades\Notification;
use App\Notifications\DatabaseNotification;
use App\User;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/notify',function(){
	$users = User::all();
	$letter = Collect([
					'title'=>'New Poilicy',
					'body'=>'Please read our new TOS policies'
					]);
	Notification::send($users,new DatabaseNotification($letter));

	echo "<h1>Notification send to all Users";

});


Route::get('markasread',function(){
	Auth::user()->notifications->markAsRead();
	return redirect()->back();
})->name('markAsRead');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('verify/{email}/{token}','Auth\RegisterController@verifyUser')->name('verify');
