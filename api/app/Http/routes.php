<?php

Route::get('/clubs', 'ClubController@index');
Route::get('/clubs/{club}', 'ClubController@show');
Route::post('/clubs', 'ClubController@store');
Route::delete('/clubs/{club}', 'ClubController@destroy');

Route::get('/members', 'MemberController@index');
Route::get('/members/{member}', 'MemberController@show');
Route::post('/members', 'MemberController@store');
Route::delete('/members/{member}', 'MemberController@destroy');
Route::patch('/members/{member}', 'MemberController@update');
