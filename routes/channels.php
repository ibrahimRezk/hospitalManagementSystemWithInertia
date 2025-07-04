<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


//////////////// new added for authorization //////////// works with Echo /////////////////
Broadcast::channel('create-invoice.{doctor_id}', function ($user,  $doctor_id) {
    return $user->id == $doctor_id;
}); 

