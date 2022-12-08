<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTravelerRequest extends FormRequest
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
            'name' => 'string|max:190',
            'lastname' => 'string|max:190',
            'gender' => 'string|max:1',
            'birthday' => 'date|date_format:d-m-Y',
            'birth_place' => 'string|max:2',
            'nationality' => 'string|max:2',
            'passport_number' => ['required','string','min:5','max:10', Rule::unique('travelers')->where(function ($query) {
                return $query->where('user_id', auth()->id())->where('id', '!=', $this->route('traveler')->id);
            })],
            'document_number' => 'nullable|string|max:190',
            'email' => 'nullable|email|max:190',
            'relationship_id' => 'nullable|integer',
            'occupation_id' => 'integer',
            'residential_country' => 'string|max:2',
            'permanent_address' => 'required|string|min:5|max:190',
            'city_id' => 'required|integer',
            'zip_code' => 'nullable|integer',
            'residence_number' => 'nullable|string',
            'civil_status_id' => 'integer',
            'sector_id' => 'nullable|integer',
            'street_address' => 'nullable|string'
        ];
    }
}
