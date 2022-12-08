<?php

namespace App\Http\Controllers\Api\Eticket;

use App\Http\Controllers\Controller;
use App\Models\Eticket\Eticket;
use App\Models\Eticket\Traveler;
use App\Models\Eticket\TravelerInformation;
use App\Models\Eticket\TravelerCustomsInformation;
//use App\Models\Eticket\PublicHealth;
use App\Jobs\SendEticket;
use App\Http\Requests\StoreEticketRequest;
use App\Http\Requests\UpdateEticketRequest;
//use App\Http\HttpClient\EticketClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;



class EticketController extends Controller
{
    private $locales = ['en', 'es'];

    public function __construct()
    {
        $this->middleware('permission:eticket.show.last')->only('index');
        $this->middleware('permission:eticket.create')->only('store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get the last Eticket with the data of the first selected traveler
        $lastEticket = Eticket::where('user_id', Auth::user()->id)
            ->select('id', 'is_arrival', 'qr_code', 'created_at')
            ->with(['travelers' => function ($query) {

                // Verify if the Auth user is in the traveler list of this Eticket
                $verification = Eticket::whereHas('travelers', function ($query) {
                    $principalTraveler = Traveler::where('user_id', Auth::user()->id)->where('principal', true)->first();
                    $currentEticket = Eticket::where('user_id', Auth::user()->id)->select('id')->latest()->first();
                    $query->where('eticket_id', $currentEticket->id)->where('traveler_id', $principalTraveler->id);
                })->count();

                // If the Auth user exists in the list
                if($verification > 0){
                    $query->with('nationality')->firstWhere('principal', true);
                } else {
                    // If the Auth don't exists in the list of travelers then select the first traveler selected
                    $currentEticket = Eticket::where('user_id', Auth::user()->id)->select('id')->latest()->first();
                    $travelerInformation = TravelerInformation::where('eticket_id', $currentEticket->id)->first();
                    $query->with('nationality')->find($travelerInformation->traveler_id);
                }
            }])->latest()->first();

        if($lastEticket){
            // Travelers associated to this Eticket
            $associatedTravelers = Traveler::withTrashed()->join('eticket_traveler','eticket_traveler.traveler_id', 'travelers.id')
                ->join('countries', 'travelers.nationality_id', 'countries.id')
                ->select('travelers.id', 'travelers.name', 'lastname', 'passport_number', 'iso3 AS nationality')
                ->where('eticket_traveler.eticket_id', $lastEticket->id)
                ->where('eticket_traveler.traveler_id', '!=', $lastEticket->travelers[0]->id)->get();

            // Select all Eticket created from this user, less the last Eticket
            $records = Eticket::select('id', 'is_arrival', 'created_at')
                ->where('user_id', Auth::user()->id)
                ->where('id','!=', $lastEticket->id)
                ->orderBy('id', 'DESC')->limit(15)->get();

            // Eticket
            $eticket = [];
            $eticket["id"] = $lastEticket->id;
            $eticket["name"] = $lastEticket->travelers[0]->name. ' '.$lastEticket->travelers[0]->lastname;
            $eticket["passport"] = $lastEticket->travelers[0]->passport_number;
            $eticket["nationality"] = $lastEticket->travelers[0]->nationality->name;
            $eticket["is_arrival"] = $lastEticket->is_arrival;
            $eticket["qr_code"] = $lastEticket->qr_code;
            $eticket["travelers"] = $associatedTravelers;
            $eticket["created_at"] = Carbon::parse($lastEticket->created_at)->format('Y-m-d');
            
            return response()->json(['eticket' => $eticket, 'records' => $records], 200);

        } else {
            return response()->json(['errors' => "You don't have E-ticket created."], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEticketRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEticketRequest $request)
    {
        $qrCode = null;
        $travelersId = [];
        //$migratoryInformation = null;
        //$publicHealthInfo = null;
        //$eticketClient = new EticketClient();

        // Create E-ticket
        $eticket = Eticket::create([
            'is_arrival' => $request->is_arrival,
            'motive_id' => $request->motive_id,
            'stop_over_in_countries' => $request->stop_over_in_countries,
            'airline_id' => $request->airline_id,
            'origin_port_id' => $request->stop_over_in_countries == true ? $request->origin_port_id : null,
            'origin_flight_number' => $request->stop_over_in_countries == true ? $request->origin_flight_number : null,
            'origin_flight_date' => $request->stop_over_in_countries == true ? $request->origin_flight_date : null,
            'embarkation_port_id' => $request->embarkation_port_id,
            'disembarkation_port_id' => $request->disembarkation_port_id,
            'flight_date' => Carbon::parse($request->flight_date)->format('Y-m-d'),
            'flight_number' => $request->flight_number,
            'flight_confirmation_number' => $request->flight_confirmation_number,
            'user_id' => Auth::user()->id,
        ]);

        // Save custom information of Travelers
        foreach ($request->traveler_information as $key => $travelerInformation) {
            
            array_push($travelersId, $travelerInformation["traveler_id"]);
            $currentTraveler = Traveler::find($travelerInformation["traveler_id"]);
            
            // Verify if exists is_task_return in array
            $isTaskReturn = array_key_exists('is_task_return', $travelerInformation) && $travelerInformation['is_task_return'] == true;
            
            // Create TravelerInformation
            $currentTravelerInformation = TravelerInformation::create([
                'traveler_id' => $travelerInformation['traveler_id'],
                'day_of_staying' => $travelerInformation['day_of_staying'],
                'particular_staying' => array_key_exists('particular_staying', $travelerInformation) ? $travelerInformation['particular_staying'] : false,
                'is_task_return' => array_key_exists('is_task_return', $travelerInformation) ? $travelerInformation['is_task_return'] : false,
                'document_number' => $isTaskReturn ? $travelerInformation['document_number'] : null,
                'phone_number' => $isTaskReturn ? $travelerInformation['phone_number'] : null,
                'air_ticket_number' => $isTaskReturn ? $travelerInformation['air_ticket_number'] : null,
                'eticket_id' => $eticket->id
            ]);

            // If isn't the principal traveler and has the common address
            if($key > 0 && array_key_exists('has_common_address', $travelerInformation) && $travelerInformation['has_common_address'] == true){
                $mainTravelerInformation = TravelerInformation::firstWhere('eticket_id', $eticket->id);
                $currentTravelerInformation->update([
                    'has_common_address' => true,
                    'hotel_id' => $mainTravelerInformation->hotel_id,
                    'sector_id' => $mainTravelerInformation->sector_id,
                    'street_address' => $mainTravelerInformation->street_address,
                    'particular_staying' => $mainTravelerInformation->particular_staying
                ]);
            } else {
                // If the traveler is Dominican then have street address 
                if($currentTraveler->residentialCountry->iso2 == 'DO'){
                    if(array_key_exists('particular_staying', $travelerInformation) && $travelerInformation['particular_staying'] != false) {
                        $currentTravelerInformation->update([
                            'sector_id' => $currentTraveler->sector_id,
                            'street_address' => $currentTraveler->street_address,
                            'particular_staying' => true
                        ]);
                    } else if(array_key_exists('hotel_id', $travelerInformation) && $travelerInformation['hotel_id'] != null) {
                        $currentTravelerInformation->update([
                            'hotel_id' => $travelerInformation['hotel_id']
                        ]);
                    } else {
                        $currentTravelerInformation->update([
                            'sector_id' => $currentTraveler->sector_id,
                            'street_address' => $currentTraveler->street_address
                        ]);
                    }
                } else {
                    if(array_key_exists('particular_staying', $travelerInformation) && $travelerInformation['particular_staying'] != null) {
                        $currentTravelerInformation->update([
                            'particular_staying' => $travelerInformation['particular_staying'],
                            'sector_id' => array_key_exists('sector_id', $travelerInformation) ? $travelerInformation['sector_id'] : null,
                            'street_address' => array_key_exists('street_address', $travelerInformation) ? $travelerInformation['street_address'] : null
                        ]);
                    } else if(array_key_exists('hotel_id', $travelerInformation) && $travelerInformation['hotel_id'] != null) {
                        $currentTravelerInformation->update([
                            'hotel_id' =>  $travelerInformation['hotel_id']
                        ]);
                    } else {
                        $currentTravelerInformation->update([
                            'sector_id' => array_key_exists('sector_id', $travelerInformation) ? $travelerInformation['sector_id'] : null,
                            'street_address' => array_key_exists('street_address', $travelerInformation) ? $travelerInformation['street_address'] : null
                        ]);
                    }
                }
            }
        }

        // Assign travelers to E-ticket
        $eticket->travelers()->attach($travelersId);

        // Save public health information of Traveler if the flight is arrival
        /*if($request->is_arrival && $request->public_health != null){
            foreach ($request->public_health as $health) {

               $publicHealth = PublicHealth::create([
                    'symptoms_date' => array_key_exists('symptoms_date', $health) ? Carbon::parse($health['symptoms_date'])->format('Y-m-d') : null,
                    'phone_number' => array_key_exists('phone_number', $health) ? $health['phone_number'] : null,
                    'specification' => array_key_exists('specification', $health) ? $health['specification'] : null,
                    'traveler_id' => $health['traveler_id'],
                    'eticket_id' => $eticket->id,
                ]);
                $publicHealth->symptoms()->attach($health['symptoms']);
            }
        }*/

        // Save customs information of Traveler if the flight is arrival
        if($request->is_arrival && $request->customs_information != null){
            
            foreach ($request->customs_information as $customsInformation) {

                $travelerCustomsInformation = new TravelerCustomsInformation(); 

                $travelerCustomsInformation->exceeds_money_limit = $customsInformation['exceeds_money_limit'];
                $travelerCustomsInformation->animals_or_food = $customsInformation['animals_or_food'];
                $travelerCustomsInformation->merch_with_tax_value = $customsInformation['merch_with_tax_value'];

                if($customsInformation['exceeds_money_limit']){
                    $travelerCustomsInformation->amount = $customsInformation['amount'];
                    $travelerCustomsInformation->currency_type_id = $customsInformation['currency_type_id'];
                    $travelerCustomsInformation->declared_origin_value = $customsInformation['declared_origin_value'];
                    $travelerCustomsInformation->is_values_owner = $customsInformation['is_values_owner'];

                    if(!$customsInformation['is_values_owner']){
                        $travelerCustomsInformation->sender_name = $customsInformation['sender_name'];
                        $travelerCustomsInformation->sender_lastname = $customsInformation['sender_lastname'];
                        $travelerCustomsInformation->receiver_name = $customsInformation['receiver_name'];
                        $travelerCustomsInformation->receiver_lastname = $customsInformation['receiver_lastname'];
                        $travelerCustomsInformation->receiver_relationship = $customsInformation['receiver_relationship'];
                        $travelerCustomsInformation->worth_destiny = $customsInformation['worth_destiny'];
                    }
                }

                if($customsInformation['merch_with_tax_value']){
                    /// Filter array elements
                    $declaredMerch = [];
                    foreach ($customsInformation['declared_merch'] as $value) {
                        array_push($declaredMerch, array ('merch_description' => $value['merch_description'], 'dollars_value' =>  $value['dollars_value']));
                    }

                    $travelerCustomsInformation->value_of_merchandise = $customsInformation['value_of_merchandise'];
                    $travelerCustomsInformation->merchandise_type_id = $customsInformation['merchandise_type_id'];
                    $travelerCustomsInformation->declared_merch = json_encode($declaredMerch,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
                }

                $travelerCustomsInformation->traveler_id = $customsInformation['traveler_id'];
                $travelerCustomsInformation->eticket_id = $eticket->id;

                if($travelerCustomsInformation->exceeds_money_limit || $travelerCustomsInformation->animals_or_food || $travelerCustomsInformation->merch_with_tax_value){
                    $travelerCustomsInformation->save();
                }
            }
        }

        // Select the principal traveler of this User
        $principalTraveler = Traveler::where('user_id', Auth::user()->id)->where('principal', true)->first();

        // Select the main traveler for response
        $mainTravelerIdFromEticket = in_array($principalTraveler->id, $travelersId) ? $principalTraveler->id : $request->traveler_information[0]["traveler_id"];
        $traveler = Traveler::with('nationality', 'residentialCountry', 'city')->find($mainTravelerIdFromEticket);

        // HTTP Get ApplicationId
        /*$requestEticket = $eticketClient->getApplicationId();

        if(is_array($requestEticket)){
            return response()->json($requestEticket, 404);
        }

        // HTTP Post to Step one
        $eticketStepOne = $eticketClient->eticketStepOne([
            "ApplicationId" => $requestEticket->applicationId,
            "CityId" => $traveler->city_id,
            "CityOfResidence" => $traveler->city->name,
            "CountryResidence" => $traveler->residentialCountry->name,
            "Id" => $requestEticket->id,
            "IsArrival" => $eticket->is_arrival,
            "PermanentResidenceAdress" => $traveler->permanent_address,
            "State" => $traveler->city->name,
            "StopOverInCountries" => $eticket->stop_over_in_countries,
            "Token" => $requestEticket->token,
            "WillStayAtResort" => false,
            "ZipCode" => $traveler->zip_code
        ]);

        if(is_array($eticketStepOne)){
            return response()->json($eticketStepOne, 404);
        }

        // HTTP Post to Step two
        foreach($travelersId as $key => $travelerId) {
            $stepTwoTraveler = Traveler::with(['nationality', 'birthPlace', 'sector', 'travelerInformation' => function($q) use ($eticket) {
                $q->where('eticket_id', $eticket->id);
            }])->find($travelerId);

            $eticketStepTwo = $eticketClient->eticketStepTwo([
                'GenericInformation' => [
                    'Companions' => count($travelersId) - 1,
                    'IsArrival' => $eticket->is_arrival
                ],
                'MigratoryInformation' => [
                    'Names' => $stepTwoTraveler->name,
                    'LastNames' => $stepTwoTraveler->lastname,
                    'BirthDate' => $stepTwoTraveler->birthday,
                    'Gender'=> $stepTwoTraveler->gender,
                    'Nationality'=> $stepTwoTraveler->nationality->iso3,
                    'BirthPlace'=> $stepTwoTraveler->birthPlace->iso3,
                    'PassportNumber'=> $stepTwoTraveler->passport_number,
                    'PassportConfirmation'=> $stepTwoTraveler->passport_number,
                    'DocumentIdNumber'=> $stepTwoTraveler->document_number,
                    'GeoCode'=> $stepTwoTraveler->sector->geo_code,
                    'Street'=> $stepTwoTraveler->first_address,
                    'MaritalStatusId'=> $stepTwoTraveler->civil_status_id,
                    'FlightMotiveId'=> $eticket->motive_id,
                    'ApplicationId'=> $requestEticket->applicationId,
                    'OcupationId'=> $stepTwoTraveler->occupation_id,
                    'AirlineId'=> $eticket->airline_id,
                    'EmbarkationPortNavId'=> $eticket->embarkation_port_id,
                    'DisembarkationPortNavId'=> $eticket->disembarkation_port_id,
                    'OriginPortNavId'=> $eticket->embarkation_port_id,
                    'OriginPort'=> $eticket->stop_over_in_countries && $eticket->is_arrival ? $eticket->origin_port_id : null,
                    'OriginFlightNumber'=> $eticket->flight_number,
                    'OriginFlightDate'=> $eticket->flight_date,
                    'EmbarkationPort'=> null,
                    'EmbarcationFlightNumber'=> $eticket->flight_number,
                    'DisembarkationFligthNumber'=> null,
                    'EmbarcationDate'=> $eticket->flight_date,
                    'DisembarkationPort' => null,
                    'DisembarkationPortFligthNumber' => null,
                    'TransportationCompany' => null,
                    'DaysOfStaying' => $stepTwoTraveler->travelerInformation[0]->day_of_staying,
                    'SpecificFlightMotive' => null,
                    'IsPrincipal' => $traveler->id == $stepTwoTraveler->id ? true : false,
                    'HasCommonAddress' => false,
                    'IsParticularStaying' => $stepTwoTraveler->travelerInformation[0]->particular_staying,
                    'HasCommonHotel' => false,
                    'WillStayAtResort' => $stepTwoTraveler->travelerInformation[0]->hotel_id != null ? true : false,
                    'HotelId'=> $stepTwoTraveler->travelerInformation[0]->hotel_id,
                    'ConfirmationNumber' => $eticket->flight_confirmation_number,
                    'Email' => $stepTwoTraveler->email,
                    'IsResident' => $stepTwoTraveler->residence_number != null ? true : false,
                    'ResidenceNumber' => $stepTwoTraveler->residence_number,
                    'FlightMotive' => null,
                    'CustomsInformation' => null,
                    'Hotel' => null,
                    'Ocupation' => null,
                    'PublicHealth' => null,
                    'MaritalStatus' => null,
                    'Sector' => null,
                    'Application' => null,
                    'Airline' => null,
                    'TaxReturnInfo' => [
                        'Id' => null,
                        'Cedula' => null,
                        'Telefono' => null,
                        'MigratoryInformationId' => 0,
                        'MigratoryInformation' => null
                    ],
                    'PersonIndex' => $key + 1,
                    'StopOverInCountries' => $eticket->stop_over_in_countries,
                    'IsArrival' => $eticket->is_arrival,
                    'FullName' => $stepTwoTraveler->fullname,
                    'Id' => 0
                ],
                'IsPrincipal' => $traveler->id == $stepTwoTraveler->id ? true : false,
                'Companions' => count($travelersId) - 1,
                'PersonIndex' => $key + 1,
                'TotalCreated' => 0,
                'Token' => $requestEticket->token
            ]);

            if(isset($eticketStepTwo->qrcode)) {
                
                $qrCode = $eticketStepTwo->qrcode;

            } else if(is_array($eticketStepTwo)) {

                $migratoryInformation = $eticketStepTwo;

            } else if(!(isset($eticketStepTwo->message) && $eticketStepTwo->message == 'Next Person')) {
                return response()->json($eticketStepTwo, 404);
            }
        }

        // HTTP Post to Step three
        if($request->is_arrival){

            $eticketId = $eticket->id;
            $stepThreeTravelers = Traveler::whereIn('id', $travelersId)->orderByRaw("field(id,".implode(',',$travelersId).")")->get();

            // Custom traveler Information
            foreach($stepThreeTravelers as $key => $stepThreeTraveler) {
                
                if($stepThreeTraveler->age >= 18) {

                    $customsInformationArray = null;
                    $customsInformation = TravelerCustomsInformation::where('traveler_id', $stepThreeTraveler->id)->where('eticket_id', $eticketId)->first();

                    if($customsInformation){
                        
                        $merchs = [];
                        
                        if($customsInformation->merch_with_tax_value){
                            $declaredMerchs = json_decode($customsInformation->declared_merch);
                            foreach ($declaredMerchs as $row) {
                                array_push($merchs, ["Description"=> $row->merch_description, "DollarValue"=> $row->dollars_value]);
                            }
                        }

                        $customsInformationArray = [
                            "CustomsInformation" =>  [
                                 "ApplicationId" => $requestEticket->applicationId,
                                 "MigratoryInformationId" => $migratoryInformation[$key]['migratoryInformationId'],
                                 "ExceedsMoneyLimit" => $customsInformation->exceeds_money_limit,
                                 "HasAnimalsOrFood" => $customsInformation->animals_or_food,
                                 "HasMerchWithTaxValue" => $customsInformation->merch_with_tax_value,
                                 "Ammount" => $customsInformation->exceeds_money_limit ? $customsInformation->amount : null,
                                 "CurrencyId" => $customsInformation->exceeds_money_limit ? $customsInformation->currency_type_id : null,
                                 "DeclaredOriginValue" => $customsInformation->exceeds_money_limit ? $customsInformation->declared_origin_value : null,
                                 "IsValuesOwner" => $customsInformation->is_values_owner,
                                 "SenderName" => $customsInformation->is_values_owner ? null : $customsInformation->sender_name,
                                 "SenderLastName" => $customsInformation->is_values_owner ? null : $customsInformation->sender_lastname,
                                 "ReceiverName" => $customsInformation->is_values_owner ? null : $customsInformation->receiver_name,
                                 "ReceiverLastName" => $customsInformation->is_values_owner ? null : $customsInformation->receiver_lastname,
                                 "RelationShip" => $customsInformation->is_values_owner ? null : $customsInformation->receiver_relationship,
                                 "WorthDestiny" => $customsInformation->is_values_owner ? null : $customsInformation->worth_destiny,
                                 "ValueOfMerchandise" => $customsInformation->merch_with_tax_value ? $customsInformation->value_of_merchandise : 0,
                                 "CurrencyMerchandiseId" => $customsInformation->merch_with_tax_value ? $customsInformation->merchandise_type_id : null,
                                 "PersonIndex" => $key + 1,
                                 "Token" => $requestEticket->token,
                                 "Id" => $migratoryInformation[$key]['id'],
                            ],
                            "DeclaredMerchs" => $customsInformation->merch_with_tax_value ? $merchs : []
                        ];
                        
                    } else {
                        $customsInformationArray = [
                            "CustomsInformation" =>  [
                                 "ApplicationId" => $requestEticket->applicationId,
                                 "MigratoryInformationId" => $migratoryInformation[$key]['migratoryInformationId'],
                                 "ExceedsMoneyLimit" => false,
                                 "HasAnimalsOrFood" => false,
                                 "HasMerchWithTaxValue" => false,
                                 "Ammount" => null,
                                 "CurrencyId" => null,
                                 "DeclaredOriginValue" => null,
                                 "IsValuesOwner" => false,
                                 "SenderName" => null,
                                 "SenderLastName" => null,
                                 "ReceiverName" => null,
                                 "ReceiverLastName" => null,
                                 "RelationShip" => null,
                                 "WorthDestiny" => null,
                                 "ValueOfMerchandise" => 0,
                                 "CurrencyMerchandiseId" => null,
                                 "PersonIndex" => $key + 1,
                                 "Token" => $requestEticket->token,
                                 "Id" => $migratoryInformation[$key]['id'],
                            ],
                            "DeclaredMerchs" => []
                        ];
                    }

                    $eticketStepThree = $eticketClient->eticketStepThree($customsInformationArray);

                    if(is_array($eticketStepThree)){
                        
                        $publicHealthInfo = $eticketStepThree;

                    } else if(!(isset($eticketStepThree->message) && $eticketStepThree->message == 'Next Person')){
                        return response()->json($eticketStepThree, 404);
                    }
                }
            }

            // Public Health 
            /*foreach($stepThreeTravelers as $key => $stepFourTraveler) {

                $symptoms = [];
                $publicHealth = PublicHealth::with('symptoms')->where('traveler_id', $stepFourTraveler->id)->where('eticket_id', $eticketId)->first();
                $publicHealthSymptoms = $publicHealth->symptoms;

                foreach ($publicHealthSymptoms as $value) {
                    array_push($symptoms, $value->id);
                }

                $eticketStepFour = $eticketClient->eticketStepFour([
                    "PublicHealth" => [
                        "ApplicationId" => $requestEticket->applicationId,
                        "MigratoryInformationId" => $publicHealthInfo[$key]['migratoryInformationId'],
                        "PhoneNumber" => $publicHealth->phone_number,
                        "SpecificSymptoms" => $publicHealth->specification,
                        "SymptomsDate" => $publicHealth->symptoms_date,
                        "QuestionResponse" => null,
                        "PersonIndex" => $key + 1,
                        "IsUnderAge" => false,
                        "IsValid" => true,
                        "IsArrival" => $request->is_arrival,
                        "IsLast" => false,
                        "Token" => $requestEticket->token,
                        "Id" => $publicHealthInfo[$key]['publicHealthId'],
                    ],
                    "Symptoms" => $symptoms,
                    "Countries" => []
                ]);

                if(isset($eticketStepFour->qrcode)) {
                
                    $qrCode = $eticketStepFour->qrcode;
    
                } else if(!(isset($eticketStepFour->message) && $eticketStepFour->message == 'Next Person')) {
                    return response()->json($eticketStepTwo, 404);
                }
            }
        }*/

        // Random numbers to QR - DELETE THIS
        $qrCode = random_int(100000, 999999);
        // Update E-ticket with QR code and the applicationId
        $svgName = $this->generateQrCode($qrCode);
        $eticket->update([
            'qr_code' => $svgName,
            'application_id' => 0 /*$requestEticket->applicationId*/
        ]);

        // Travelers associated to this Eticket
        $associatedTravelers = Traveler::join('eticket_traveler','eticket_traveler.traveler_id', 'travelers.id')
        ->join('countries', 'travelers.nationality_id', 'countries.id')
        ->select('travelers.id', 'travelers.name', 'lastname', 'passport_number', 'iso3 AS nationality')
        ->where('eticket_traveler.eticket_id', $eticket->id)
        ->where('eticket_traveler.traveler_id', '!=', $traveler->id)->get();

        // Select all Eticket created from this user, less the last Eticket
        $records = Eticket::select('id', 'is_arrival', 'created_at')
            ->where('user_id', Auth::user()->id)
            ->where('id','!=', $eticket->id)
            ->orderBy('id', 'DESC')->limit(15)->get();

        // Eticket data
        $response = [];
        $response["id"] = $eticket->id;
        $response["name"] = $traveler->fullname;
        $response["passport"] = $traveler->passport_number;
        $response["nationality"] = $traveler->nationality->name;
        $response["is_arrival"] = $eticket->is_arrival;
        $response["qr_code"] = $eticket->qr_code;
        $response["travelers"] = $associatedTravelers;
        $response["created_at"] = Carbon::parse($eticket->created_at)->format('Y-m-d');

        // Generate PDF
        $locale = $request->has('locale') && in_array($request->locale, $this->locales) ? $request->locale : 'en';
        SendEticket::dispatch(Auth::user()->email, $response, $locale);

        // Return response
        return response()->json(['eticket' => $response, 'records' => $records], 201);
    }

    /**
     * Generate Qr code to E-ticket
     * Remove the specified resource from storage.
     *
     * @param  String $value
     * @return String
     */
    private function generateQrCode($value) 
    {
        // Utilizar este en desarrollo
        $name = time().'.svg';
        $imagePath = 'images/qrcodes/'.$name;
        QrCode::generate($value, $imagePath);
        return $name;

        // Descomentar esto en Produccion
        /*$name = time().'.png';
        $imagePath = 'images/qrcodes/'.$name;
        QrCode::format('png')->size(300)->generate($value, $imagePath, 'image/png');
        return $name;*/
    }
}
