<?php

Route::get('/', function () {
    return response()->json(['message' => 'Hello'], 200);
});
