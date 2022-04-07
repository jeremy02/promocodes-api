<?php

namespace App\Exceptions;

use Exception;

// throw a NotFoundException
class PromoCodeDoesNotExistException extends Exception {
    public function render() {
        $response = [
            'status' => 'error',
            'message' => "Sorry. The event promo code cannot be found.",
        ];
        return response()->json($response, 400);
    }
}
