<?php

namespace App\Exceptions;

use Exception;

// throw a Exception
class PromoCodeExpiredException extends Exception {
    public function render() {
        $response = [
            'status' => 'error',
            'message' => "Sorry. The event promo code is already expired.",
        ];
        return response()->json($response, 400);
    }
}
