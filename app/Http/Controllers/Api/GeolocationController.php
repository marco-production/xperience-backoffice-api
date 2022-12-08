<?php

namespace App\Http\Controllers\Api;

use App\Models\Geolocation\Country;
use App\Models\Geolocation\City;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeolocationController extends Controller
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->middleware('permission:geolocations.country')->only('getCountry');
        $this->middleware('permission:geolocations.city')->only('getCitiesByIso');
    }

    /**
     * Display a listing of countries.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCountry($id = null)
    {
        if(isset($id)){
            $countries = Country::find($id);
        } else {
            $countries = Country::All();
        }

        return response()->json($countries, 200);
    }

    /**
     * Display a listing of states.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCitiesByIso($iso = null)
    {
        if(isset($iso)) {
            $cities = City::where('iso2', $iso)->get();
        } else {
            $cities = City::All();
        }
        
        return response()->json($cities, 200);
    }

    /**
     * Return country_id from specific code.
     *
     * @return int id
     */
    public static function getIdFromCodeOfCountry($code)
    {
        return Country::where('iso2', $code)->pluck('id')->first();
    }

}
