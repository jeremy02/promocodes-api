<?php

namespace App\Exceptions;

use Exception;

// throw a ServiceUnavailableException
class GoogleMapsDirectionAPIException extends Exception {
    public function render() {
        $response = [
            'status' => 'error',
            'message' => "There was an error while fetching polylines",
        ];
        return response()->json($response, 503);
    }
}
