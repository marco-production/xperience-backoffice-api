<?php

namespace App\Http\Controllers\Api;

use App\Models\WeakEntities\Port;
use App\Models\WeakEntities\Hotel;
use App\Models\WeakEntities\Airline;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WeakController extends Controller
{
    /**
     * Display a listing of ports
     *
     * @return \Illuminate\Http\Response
     */
    public function getPort($id = null)
    {
        if(isset($id)){
            $port = Port::find($id);
        } else {
            $port = Port::All();
        }

        return response()->json($port, 200);
    }

    /**
     * Display a listing of airlines
     *
     * @return \Illuminate\Http\Response
     */
    public function getAirline($id = null)
    {
        if(isset($id)){
            $airline = Airline::find($id);
        } else {
            $airline = Airline::All();
        }

        return response()->json($airline, 200);
    }

        /**
     * Display a listing of hotels
     *
     * @return \Illuminate\Http\Response
     */
    public function getHotel($id = null)
    {
        if(isset($id)){
            $hotel = Hotel::find($id);
        } else {
            $hotel = Hotel::All();
        }

        return response()->json($hotel, 200);
    }
}
