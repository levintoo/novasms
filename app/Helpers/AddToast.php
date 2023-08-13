<?php

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

/**
 * @param string $message
 * @param string|null $type
 * @return void
 */
function toast(string $message,?string $type)
{
    session::flash('toast', [
        'message' => $message,
        'type' => $type ?? 'info',
        Str::random(),
    ]);
}
