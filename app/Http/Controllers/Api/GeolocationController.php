<?php

namespace App\Http\Controllers\Api;

use App\Models\Geolocation\Province;
use App\Models\Geolocation\Municipality;
use App\Models\Geolocation\Sector;
use App\Models\Geolocation\MacroRegion;
use App\Models\Geolocation\Country;
use App\Models\Geolocation\City;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeolocationController extends Controller
{
    /**
     * Constructor method
     */
    public function __construct()
    {
        $this->middleware('permission:geolocations.macro')->only('getRegionMacro');
        $this->middleware('permission:geolocations.province')->only('getProvince');
        $this->middleware('permission:geolocations.municipality')->only('getMunicipality');
        $this->middleware('permission:geolocations.sector')->only('getSector');
        $this->middleware('permission:geolocations.country')->only('getCountry');
        $this->middleware('permission:geolocations.city')->only('getCity');
    }

    /**
     * Display a listing of provinces.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProvince($id = null)
    {
        if(isset($id)){
            $province = Province::find($id);
        } else {
            $province = Province::All();
        }

        return response()->json($province, 200);
    }

    /**
     * Display a listing of municipalities.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMunicipality($id = null)
    {
        if(isset($id)){
            $municipality = Municipality::find($id);
        } else {
            $municipality = Municipality::All();
        }

        return response()->json($municipality, 200);
    }

    /**
     * Display a listing of sectors.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSector($id = null)
    {
        if(isset($id)){
            $sector = Sector::find($id);
        } else {
            $sector = Sector::All();
        }

        return response()->json($sector, 200);
    }


    /**
     * Display a listing of macro Regions.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegionMacro($id = null)
    {
        if(isset($id)){
            $macroRegion = MacroRegion::find($id);
        } else {
            $macroRegion = MacroRegion::All();
        }

        return response()->json($macroRegion, 200);
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
     * Display a listing of active countries.
     *
     * @return \Illuminate\Http\Response
     */
    public function getActiveCountry()
    {
        $countries = Country::where('enabled', true)->get();
        return response()->json($countries, 200);
    }

    /**
     * Display a listing of cities.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCity($id = null)
    {
        if(isset($id)){
            $city = City::find($id);
        } else {
            $city = City::All();
        }

        return response()->json($city, 200);
    }

    /**
     * Display a listing of cities by ISO.
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
