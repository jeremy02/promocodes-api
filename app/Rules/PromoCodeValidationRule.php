<?php

namespace App\Rules;

use App\Models\Promocode;
use Illuminate\Contracts\Validation\Rule;

class PromoCodeValidationRule implements Rule {
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value) {
        $promoCode = Promocode::where(
            [
                ['code', '=', strtoupper($value)],
                ['is_used', '=', false], // has the promo code been used
                ['is_active', '=', true],
                ['start_at', '<', now()], // if the start_at date is less time now
                ['end_at', '>', now()], // if the end_at date is greater than start_at
            ]
        )->first();

        return !!$promoCode;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() {
        return 'The promo code is not valid.';
    }
}
