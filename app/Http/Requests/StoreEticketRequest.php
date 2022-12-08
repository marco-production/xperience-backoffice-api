<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEticketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'is_arrival' => 'required|boolean',
            'motive_id' => 'required|integer',
            'stop_over_in_countries' => 'nullable|boolean',
            'airline_id' => 'required|integer',
            'origin_port_id' => ['integer', Rule::requiredIf(function () {
                return $this->request->get('is_arrival') == true && 
                       $this->request->get('stop_over_in_countries') == true ? true : false;
            })],
            'origin_flight_number' => ['string', Rule::requiredIf(function () {
                return $this->request->get('is_arrival') == true && 
                       $this->request->get('stop_over_in_countries') == true ? true : false;
            })],
            'origin_flight_date' => ['date','date_format:d-m-Y', Rule::requiredIf(function () {
                return $this->request->get('is_arrival') == true && 
                       $this->request->get('stop_over_in_countries') == true ? true : false;
            })],
            'embarkation_port_id' => 'required|integer',
            'disembarkation_port_id' => 'required|integer',
            'flight_date' => 'required|date|date_format:d-m-Y',
            'flight_number' => 'required|string|max:190',
            'flight_confirmation_number' => 'nullable|string|max:190',
            'locale' => 'nullable|string|min:2|max:2',

            // Traveler Information
            'traveler_information' => 'required|array|min:1|max:6',
            'traveler_information.*' => 'required|array',
            'traveler_information.*.traveler_id' => 'required|integer',
            'traveler_information.*.day_of_staying' => 'required|integer|min:1',
            'traveler_information.*.particular_staying' => 'nullable|boolean',
            'traveler_information.*.hotel_id' => 'nullable|integer',
            'traveler_information.*.is_task_return' => 'nullable|boolean',
            'traveler_information.*.document_number' => 'required_if:traveler_information.*.is_task_return,true|string',
            'traveler_information.*.phone_number' => 'required_if:traveler_information.*.is_task_return,true|string',
            'traveler_information.*.air_ticket_number' => 'required_if:traveler_information.*.is_task_return,true|string',
            'traveler_information.*.has_common_address' => 'nullable|boolean',
            'traveler_information.*.sector_id' => 'nullable|integer',
            'traveler_information.*.street_address' => 'required_with:traveler_information.*.sector_id|string',


            //Customs Information
            'customs_information' => 'nullable|array|required_if:is_arrival,true|max:'.count($this->traveler_information ?? []),
            'customs_information.*' => 'nullable|array',
            'customs_information.*.traveler_id' => 'required|integer',
            
            'customs_information.*.exceeds_money_limit' => 'required|boolean',
            'customs_information.*.animals_or_food' => 'required|boolean',
            'customs_information.*.merch_with_tax_value' => 'required|boolean',

            'customs_information.*.amount' => 'required_if:customs_information.*.exceeds_money_limit,true|between:0,99.99',
            'customs_information.*.currency_type_id' => 'required_if:customs_information.*.exceeds_money_limit,true|integer',
            'customs_information.*.declared_origin_value' => 'required_if:customs_information.*.exceeds_money_limit,true|string',
            
            'customs_information.*.is_values_owner' => 'required_if:customs_information.*.exceeds_money_limit,true|boolean',
            'customs_information.*.sender_name' => 'required_if:customs_information.*.is_values_owner,false|string',
            'customs_information.*.sender_lastname' => 'required_if:customs_information.*.is_values_owner,false|string',
            'customs_information.*.receiver_name' => 'required_if:customs_information.*.is_values_owner,false|string',
            'customs_information.*.receiver_lastname' => 'required_if:customs_information.*.is_values_owner,false|string',
            'customs_information.*.receiver_relationship' => 'required_if:customs_information.*.is_values_owner,false|string',
            'customs_information.*.worth_destiny' => 'required_if:customs_information.*.is_values_owner,false|string',

            'customs_information.*.value_of_merchandise' => 'required_if:customs_information.*.merch_with_tax_value,true|between:0,99.99',
            'customs_information.*.merchandise_type_id' => 'required_if:customs_information.*.merch_with_tax_value,true|integer',
            'customs_information.*.declared_merch' => 'required_if:customs_information.*.merch_with_tax_value,true|array',
            'customs_information.*.declared_merch.*' => 'required|array',
            'customs_information.*.declared_merch.*.merch_description' => 'required|string',
            'customs_information.*.declared_merch.*.dollars_value' => 'required|between:0,99.99',

            // Public Health
            /*'public_health' => 'nullable|array|required_if:is_arrival,true|max:'.count($this->traveler_information ?? []),
            'public_health.*' => 'nullable|array',
            'public_health.*.traveler_id' => 'required|integer',
            'public_health.*.symptoms' => 'required|array',
            'public_health.*.symptoms.*' => 'required|integer',
            'public_health.*.symptoms_date' => 'nullable|date',
            'public_health.*.phone_number' => 'nullable|string',
            'public_health.*.specification' => 'nullable|string'*/
        ];
    }
}
