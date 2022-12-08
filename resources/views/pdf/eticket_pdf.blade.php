<!doctype html>
<html lang="en">
   <head>
      <title>Experience - Eticket</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
   </head>
   <body>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <strong>Eticket #:</strong>
                    <span>{{$eticket->id}}</span>
                </div>
            </div>
            <h4>GENERAL INFORMATION</h4>
            <table class="table table-bordered table-condensed">
                <tbody>
                    <tr>
                        <td>
                            <h6>
                                <strong>ARRIVAL OR DEPARTURE?</strong>
                            </h6>
                            <span>{{$eticket->is_arrival ? 'ARRIVAL' : 'DEPARTURE'}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h6>
                                <strong>PERMANENT ADDRESS</strong>
                            </h6>
                            <span>{{$eticket->travelers[0]->permanent_address}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h6>
                                <strong>COUNTRY OF RESIDENCE</strong>
                            </h6>
                            <span>{{$eticket->travelers[0]->residentialCountry->name}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h6>
                                <strong>CITY</strong>
                            </h6>
                            <span>{{$eticket->travelers[0]->city->name}}r</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h6>
                                <strong>STATE</strong>
                            </h6>
                            <span>{{$eticket->travelers[0]->city->state}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h6>
                                <strong>POSTAL CODE</strong>
                            </h6>
                            <span>{{$eticket->travelers[0]->zip_code}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h6>
                                <strong>DO YOU MAKE STOPS IN OTHER COUNTRIES?</strong>
                            </h6>
                            <span>
                                {{$eticket->stop_over_in_countries ? 'Si' : 'No'}}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <strong>Eticket #:</strong>
                    <span>{{$eticket->id}}</span>
                </div>
            </div>
            <h4>FLY INFORMATION</h4>
            <table class="table table-bordered table-condensed">
                <tbody>
                    @if($eticket->origin_port_id != null)
                        <tr>
                            <td>
                                <h6>
                                    <strong>ORIGIN PORT</strong>
                                </h6>
                                <span>{{$eticket->originPort->name}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h6>
                                    <strong>ORIGIN FLIGHT NUMBER</strong>
                                </h6>
                                <span>{{$eticket->origin_flight_number}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h6>
                                    <strong>ORIGIN FLIGHT DATE</strong>
                                </h6>
                                <span>{{$eticket->origin_flight_date}}</span>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td>
                            <h6>
                                <strong>EMBARKATION PORT</strong>
                            </h6>
                            <span>{{$eticket->embarkationPort->name}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h6>
                                <strong>DISEMBARKATION PORT</strong>
                            </h6>
                            <span>{{$eticket->disembarkationPort->name}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h6>
                                <strong>TRAVEL PURPOSE</strong>
                            </h6>
                            <span>{{$eticket->motive->name}}r</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h6>
                                <strong>FLIGHT NUMBER</strong>
                            </h6>
                            <span>{{$eticket->flight_number}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h6>
                                <strong>FLIGHT CONFIRMATION NUMBER</strong>
                            </h6>
                            <span>{{$eticket->flight_confirmation_number}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h6>
                                <strong>AIRLINE NAME</strong>
                            </h6>
                            <span>
                                {{$eticket->airline->name}}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h6>
                                <strong>FLIGHT DATE</strong>
                            </h6>
                            <span>{{$eticket->flight_date}}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <strong>Travelers:</strong>
                    <span>{{count($eticket->travelers)}}</span>
                </div>
            </div>
            <h4>TRAVELERS INFORMATION</h4>
            @foreach ($eticket->travelers as $traveler)
                <table class="table table-bordered table-condensed">
                    <tbody>
                        <tr>
                            <td>
                                <h6>
                                    <strong>HAS COMMON ADDRESS</strong>
                                </h6>
                                <span>{{$traveler->travelerInformation[0]->has_common_address ? 'Si' : 'No'}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h6>
                                    <strong>NAME</strong>
                                </h6>
                                <span>{{$traveler->name}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h6>
                                    <strong>LAST NAMES</strong>
                                </h6>
                                <span>{{$traveler->lastname}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h6>
                                    <strong>EMAIL</strong>
                                </h6>
                                <span>{{$traveler->email}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h6>
                                    <strong>DATE OF BIRTH</strong>
                                </h6>
                                <span>{{$traveler->birthday}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h6>
                                    <strong>GENDER</strong>
                                </h6>
                                <span>{{$traveler->gender}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h6>
                                    <strong>PLACE OF BIRTH</strong>
                                </h6>
                                <span>{{$traveler->birthPlace->name}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h6>
                                    <strong>COUNTRY OF NATIONALITY</strong>
                                </h6>
                                <span>{{$traveler->nationality->name}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h6>
                                    <strong>PASSPORT NUMBER</strong>
                                </h6>
                                <span>{{$traveler->passport_number}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h6>
                                    <strong>CIVIL STATUS</strong>
                                </h6>
                                <span>{{$traveler->civilStatus->name}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h6>
                                    <strong>OCUPATION</strong>
                                </h6>
                                <span>{{$traveler->occupation->name}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h6>
                                    <strong>ARE YOU A FOREIGNER RESIDENT IN THE DOMINICAN REPUBLIC?</strong>
                                </h6>
                                <span>{{$traveler->residence_number != null ? 'Si' : 'No'}}</span>
                            </td>
                        </tr>
                        @if($traveler->residence_number != null)
                            <tr>
                                <td>
                                    <h6>
                                        <strong>RESIDENCE NUMBER</strong>
                                    </h6>
                                    <span>{{$traveler->residence_number}}</span>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>
                                <h6>
                                    <strong>ARE YOU LODGING IN A PRIVATE RENTAL? (e.g : Airbnb)</strong>
                                </h6>
                                <span>{{$traveler->travelerInformation[0]->particular_staying ? 'Si' : 'No'}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h6>
                                    <strong>ARE YOU GOING TO STAY AT A HOTEL?</strong>
                                </h6>
                                <span>{{$traveler->travelerInformation[0]->hotel_id != null ? 'Si' : 'No'}}</span>
                            </td>
                        </tr>
                        <tr>
                            @if($traveler->travelerInformation[0]->hotel_id != null)
                            <td>
                                <h6>
                                    <strong>HOTEL</strong>
                                </h6>
                                <span>{{$traveler->travelerInformation[0]->hotel->name}}</span>
                            </td>
                            @endif
                        </tr>
                        @if ($traveler->travelerInformation[0]->sector_id != null)
                            <tr>
                                <td>
                                    <h6>
                                        <strong>PROVINCE</strong>
                                    </h6>
                                    <span>{{$traveler->travelerInformation[0]->sector->municipality->province->name}}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>
                                        <strong>MUNICIPALITY</strong>
                                    </h6>
                                    <span>{{$traveler->travelerInformation[0]->sector->municipality->name}}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>
                                        <strong>SECTION</strong>
                                    </h6>
                                    <span>{{$traveler->travelerInformation[0]->sector->name}}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>
                                        <strong>STREET AND NUMBER</strong>
                                    </h6>
                                    <span>{{$traveler->travelerInformation[0]->street_address}}</span>
                                </td>
                            </tr> 
                        @endif
                        <tr>
                            <td>
                                <h6>
                                    <strong>DAYS OF STAYING</strong>
                                </h6>
                                <span>{{$traveler->travelerInformation[0]->day_of_staying}}</span>
                            </td>
                        </tr>
                        @if ($traveler->travelerInformation[0]->is_task_return)
                        <tr>
                            <td>
                                <h6>
                                    <strong>Do you want to apply for the US\$10 refund for the tourist card?</strong>
                                </h6>
                                <span>{{$traveler->travelerInformation[0]->is_task_return ? 'Si' : 'No'}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h6>
                                    <strong>Document number (Cedula)</strong>
                                </h6>
                                <span>{{$traveler->travelerInformation[0]->document_number}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h6>
                                    <strong>Phone number</strong>
                                </h6>
                                <span>{{$traveler->travelerInformation[0]->phone_number}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h6>
                                    <strong>Air ticket number</strong>
                                </h6>
                                <span>{{$traveler->travelerInformation[0]->air_ticket_number}}</span>
                            </td>
                        </tr> 
                        @endif
                    </tbody>
                </table>
                <br>
                @if ($eticket->is_arrival == true && $eticket->customsInformation != null)
                    <h3>CUSTOMS INFORMATION (ADUANAS)</h3>
                    <table class="table table-bordered table-condensed">
                        <tbody>
                            <tr>
                                <td>
                                    <h6>
                                        <strong>DO YOU BRING OR BRING WITH YOU OR IN YOUR LUGGAGE (S), YOU AND / OR YOUR FAMILY MEMBERS, CURRENCY VALUES OR ANOTHER PAYMENT INSTRUMENT, AN AMOUNT IN EXCESS OF USD $ 10,000.00 OR ITS EQUIVALENT IN ANOTHER ( S) TYPE (S) OF CURRENCY (S)?</strong>
                                    </h6>
                                    <span>{{$traveler->customsInformation[0]->exceeds_money_limit ? 'Si' : 'No'}}</span>
                                </td>
                            </tr>
                            @if ($traveler->customsInformation[0]->exceeds_money_limit)
                                <tr>
                                    <td>
                                        <h6>
                                            <strong>AMMOUNT</strong>
                                        </h6>
                                        <span>{{$traveler->customsInformation[0]->amount}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h6>
                                            <strong>CURRENCY</strong>
                                        </h6>
                                        <span>{{$traveler->customsInformation[0]->currency_type_id}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h6>
                                            <strong>DECLARE ORIGIN OF THE SECURITIES</strong>
                                        </h6>
                                        <span>{{$traveler->customsInformation[0]->amount}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h6>
                                            <strong>ARE YOU THE OWNER OF THE VALUES YOU CARRY?</strong>
                                        </h6>
                                        <span>{{$traveler->customsInformation[0]->is_values_owner ? 'Si' : 'No'}}</span>
                                    </td>
                                </tr>
                                @if ($traveler->customsInformation[0]->is_values_owner)
                                    <tr>
                                        <td>
                                            <h6>
                                                <strong>SENDER NAME</strong>
                                            </h6>
                                            <span>{{$traveler->customsInformation[0]->sender_name}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6>
                                                <strong>SENDER LAST NAME</strong>
                                            </h6>
                                            <span>{{$traveler->customsInformation[0]->sender_lastname}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6>
                                                <strong>RECEIVER NAME</strong>
                                            </h6>
                                            <span>{{$traveler->customsInformation[0]->receiver_name}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6>
                                                <strong>RECEIVER LAST NAME</strong>
                                            </h6>
                                            <span>{{$traveler->customsInformation[0]->receiver_lastname}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6>
                                                <strong>RELATIONSHIP WITH SENDER OR RECEIVER</strong>
                                            </h6>
                                            <span>{{$traveler->customsInformation[0]->receiver_relationship}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h6>
                                                <strong>USE OR DESTINY OF THE MONEY OR VALUES</strong>
                                            </h6>
                                            <span>{{$traveler->customsInformation[0]->declared_origin_value}}</span>
                                        </td>
                                    </tr>
                                @endif
                            @endif
                            <tr>
                                <td>
                                    <h6>
                                        <strong>DO YOU BRING WITH YOU OR IN YOUR LUGGAGE LIVE ANIMALS, PLANTS OR FOOD PRODUCTS?</strong>
                                    </h6>
                                    <span>{{$traveler->customsInformation[0]->animals_or_food ? 'Si' : 'No'}}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>
                                        <strong>DO YOU BRING WITH YOU OR IN YOUR BAGGAGE GOODS SUBJECT TO TAX PAYMENT?</strong>
                                    </h6>
                                    <span>{{$traveler->customsInformation[0]->merch_with_tax_value ? 'Si' : 'No'}}</span>
                                </td>
                            </tr>
                            @if ($traveler->customsInformation[0]->merch_with_tax_value)
                            <tr>
                                <td>
                                    <h6>
                                        <strong>APROXIMATED VALUE</strong>
                                    </h6>
                                    <span>{{$traveler->customsInformation[0]->value_of_merchandise}}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>
                                        <strong>CURRENCY</strong>
                                    </h6>
                                    <span>{{$traveler->customsInformation[0]->merchandise_type_id}}</span>
                                </td>
                            </tr>
                            <table class="table">
                                <thead class="thead-dark">
                                  <tr>
                                    <th scope="col">MERCHANDISE DESCRIPTION	</th>
                                    <th scope="col">VALUE IN DOLLARS</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $merchandise = json_decode($traveler->customsInformation[0]->declared_merch);
                                    @endphp
                                    @foreach ($merchandise as $merch)
                                        <tr>
                                            <td>{{ $merch->merch_description }}</td>
                                            <td>{{ $merch->dollars_value }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                              </table>
                            @endif
                        </tbody>
                    </table>
                @endif
                <hr style="border-top: 1px dotted red;">
            @endforeach
        </div>
   </body>
</html>