<?php

namespace App\Http\Controllers\Api\Eticket;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\GeolocationController;
use Carbon\Carbon;
use App\Http\Requests\StoreTravelerRequest;
use App\Http\Requests\UpdateTravelerRequest;
use App\Models\Eticket\Traveler;
use App\Models\User;

class TravelerController extends Controller
{
    /**
     * Constructor method
     */
    public function __construct()
    {
        $this->middleware('permission:traveler.index')->only('index');
        $this->middleware('permission:traveler.create')->only('store');
        $this->middleware('permission:traveler.show')->only('show');
        $this->middleware('permission:traveler.update')->only('update');
        $this->middleware('permission:traveler.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $travelers = Traveler::All();
        return response()->json($travelers, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTravelerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTravelerRequest $request)
    {
        $isPrincipal = false;
        $user = User::findOrFail($request->user_id);
        
        // Verify if this is the principal traveler
        if(Traveler::where('user_id', $user->id)->count() == 0) { $isPrincipal = true; }

        $geolocationController = new GeolocationController();

        $request->merge(['birthday' => Carbon::parse($request->birthday)->format('Y-m-d')]);
        $request->merge(['birth_place_id' => $geolocationController->getIdFromCodeOfCountry($request->birth_place)]);
        $request->merge(['nationality_id' => $geolocationController->getIdFromCodeOfCountry($request->nationality)]);
        $request->merge(['residential_country_id' => $geolocationController->getIdFromCodeOfCountry($request->residential_country)]);
        $request->merge(['relationship_id' => $isPrincipal ? 5 : $request->relationship_id]);
        $request->merge(['street_address' => $request->filled('street_address') ? $request->street_address : $request->permanent_address]);
        $request->merge(['principal' => $isPrincipal]);
        $request->merge(['user_id' => $user->id]);

        // Create traveler
        $traveler = Traveler::create($request->toArray());

        /// Update country of user if the principal es distint
        if($isPrincipal && $traveler->residential_country_id != $user->country_id){
            $user->update(['country_id' => $traveler->residential_country_id]);
        }

        return response()->json($traveler, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Traveler  $traveler
     * @return \Illuminate\Http\Response
     */
    public function show(Traveler $traveler)
    {
        return response()->json($traveler, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTravelerRequest  $request
     * @param  \App\Models\Traveler  $traveler
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTravelerRequest $request, Traveler $traveler)
    {
        $geolocationController = new GeolocationController();

        $traveler->update([
            'name' => $request->filled('name') ? $request->name : $traveler->name,
            'lastname' => $request->filled('lastname') ? $request->lastname : $traveler->lastname,
            'gender' => $request->filled('gender') ? $request->gender : $traveler->gender,
            'birthday' => $request->filled('birthday') ? Carbon::parse($request->birthday)->format('Y-m-d') : $traveler->birthday,
            'birth_place_id' => $request->filled('birth_place') ? $geolocationController->getIdFromCodeOfCountry($request->birth_place) : $traveler->birth_place_id,
            'nationality_id' => $request->filled('nationality') ? $geolocationController->getIdFromCodeOfCountry($request->nationality) : $traveler->nationality_id,
            'passport_number' => $request->filled('passport_number') ? $request->passport_number : $traveler->passport_number,
            'document_number' => $request->has('document_number') ? $request->document_number : $traveler->document_number,
            'email' => $request->has('email') ? $request->email : $traveler->email,
            'relationship_id' => $request->has('relationship_id') && $request->relationship_id != null ? $request->relationship_id : $traveler->relationship_id,
            'occupation_id' => $request->filled('occupation_id') ? $request->occupation_id : $traveler->occupation_id,
            'residential_country_id' => $request->filled('residential_country') ? $geolocationController->getIdFromCodeOfCountry($request->residential_country) : $traveler->residential_country_id,
            'permanent_address' => $request->filled('permanent_address') ? $request->permanent_address : $traveler->permanent_address,
            'city_id' => $request->filled('city_id') ? $request->city_id : $traveler->city_id,
            'zip_code' => $request->has('zip_code') ? $request->zip_code : $traveler->zip_code,
            'residence_number' => $request->has('residence_number') ? $request->residence_number : $traveler->residence_number,
            'street_address' => $request->filled('street_address') ? $request->street_address : $traveler->permanent_address,
            'civil_status_id' => $request->filled('civil_status_id') ? $request->civil_status_id : $traveler->civil_status_id,
            'sector_id' => $request->filled('sector_id') ? $request->sector_id : $traveler->sector_id,
        ]);

        /// Update country of user if the principal es distint
        $user = User::find($traveler->user_id);

        if($traveler->principal && $traveler->residential_country_id != $user->country_id){
            $user->update(['country_id' => $traveler->residential_country_id]);
        }

        return response()->json($traveler, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Traveler  $traveler
     * @return \Illuminate\Http\Response
     */
    public function destroy(Traveler $traveler)
    {
        $traveler->delete();
        return response()->json('Traveler deleted successfully.', 200);
    }
}
