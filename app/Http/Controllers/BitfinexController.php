<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BitfinexController extends Controller
{
    public function getSymbols(){
        $response = Http::get('https://api.bitfinex.com/v1/symbols');
        return $response;
    }

    public function getDetails($pair){
        $response = Http::get('https://api.bitfinex.com/v1/symbols/'.$pair);
        return $response;
    }
}
