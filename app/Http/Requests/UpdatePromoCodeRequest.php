<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePromoCodeRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'title' => 'required|string',
            'code' => ['required','string', 'min:6', 'max:20', 'regex:/^[A-Z0-9]+$/','unique:promocodes,code,'.$this->promocode->id],
            'discount_amount' => 'required|numeric|min:50|max:1500',
            'radius' => 'required|numeric|min:1|max:100',
            'radius_unit' => 'required|in:meter,km',
            'start_at' => 'required|date', // |before:now
            'end_at' => 'required|date|after:start_at,now',
        ];
    }
}
