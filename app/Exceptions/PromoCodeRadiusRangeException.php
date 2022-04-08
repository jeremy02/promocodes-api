<?php

namespace App\Exceptions;

use Exception;

// throw a Exception
class PromoCodeRadiusRangeException extends Exception {
    public function render() {
        $response = [
            'status' => 'error',
            'message' => "Sorry. The event is not within the range and promo code cannot be applied.",
        ];
        return response()->json($response, 400);
    }
}
