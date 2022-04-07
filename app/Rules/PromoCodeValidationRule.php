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
                ['start_at', '<', now()], // if the event start_at is less than now
                ['end_at', '>', now()], // if the end_at date is greater than now
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
        return 'The promo code is not valid or cannot be used.';
    }
}
