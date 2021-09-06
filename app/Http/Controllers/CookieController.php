<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;

class CookieController extends Controller
{
    // https://www.nicesnippets.com/blog/laravel-cookies-set-get-delete-cookies
    public function login()
    {
        $response = new  Response('');
        return $response->withCookie(cookie()->forever('logedIn', true));
    }

    public function handleFavorites($fav)
    {
        $isLoged=Cookie::get('logedIn');
        if($isLoged)
        {
            // get cookie and read it
            $favorites = unserialize(Cookie::get('favorites')) ?? [];
           
            // toggle favorites
            if(isset($favorites["$fav"]))
            {
                unset($favorites["$fav"]);
            }
            else
            {
                $favorites["$fav"]=true;
            }
            // create cookie
            $response = new  Response('');
            return $response->withCookie(cookie()->forever('favorites', serialize($favorites)));
        }
    }

    public function status()
    {       
        return json_encode(['isLoged'=>Cookie::get('logedIn'), 'favorites'=> unserialize(Cookie::get('favorites')) ?? [] ]);
    }
}
