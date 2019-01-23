<?php

namespace Jiri\Configuration;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Jiri\Event;

trait JiriVariables
{
    /**
     * Get the default javascript variables
     */
    
    public static function jiriVariables()
    {
        return [
            'locale' => App::getLocale(),
            'csrfToken' => csrf_token(),
            'currentUser' => Auth::user()
        ];
    }
    
}
