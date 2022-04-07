<?php

namespace App\Http\Requests;

use App\Rules\PromocodeValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CheckValidPromoCodeRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the check if promo code is valid request.
     *
     * @return array
     */
    public function rules() {
        return [
            'code' => ['required', 'string', new PromoCodeValidationRule()],
            'origin_latitude' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'origin_longitude' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'destination_latitude' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'destination_longitude' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
        ];
    }

    public function messages() {
        return [
            // 'code.required' => 'The promo code is required', // message replaces by PromoCodeValidationRule
            'origin_latitude.regex' => 'Incorrect format for Origin Latitude',
            'origin_longitude.regex' => 'Incorrect format for Origin Longitude',
            'destination_latitude.regex' => 'Incorrect format for Destination Latitude',
            'destination_longitude.regex' => 'Incorrect format for Destination Longitude',
        ];
    }

}
