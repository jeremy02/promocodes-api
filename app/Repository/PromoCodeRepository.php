<?php

namespace App\Repository;

use App\Exceptions\GoogleMapsDirectionAPIException;
use App\Exceptions\PromoCodeDoesNotExistException;
use App\Exceptions\PromoCodeExpiredException;
use App\Exceptions\PromoCodeRadiusRangeException;
use App\Http\Resources\Promocode as PromocodeResource;
use App\Models\Promocode;

class PromoCodeRepository extends BaseRepository
{
    protected $model = Promocode::class;

    /**
     * @param array $request
     * @return array
     * @throws GoogleMapsDirectionAPIException
     * @throws PromoCodeRadiusRangeException
     * @throws PromoCodeExpiredException
     * @throws PromoCodeDoesNotExistException
     */
    public function checkvalid(array $request) {
        // Get Promo Code and only promo codes that are active
        $promoCode = Promocode::where('code', $request['code'])->where('is_active', true)->first();

        // does the promo code exist
        if(!$promoCode) {
            throw new PromoCodeDoesNotExistException();
        }

        // Check if the promo code is expired
        // Please note that this has already been handled by PromoCodeValidationRule
        if($promoCode->end_at < now()) {
            throw new PromoCodeExpiredException();
        }

        // create the origin LatLng object
        $originLatLong = [
            'latitude' => $request['origin_latitude'],
            'longitude' => $request['origin_longitude']
        ];

        // create the destination LatLng object
        $destinationLatLong = [
            'latitude' => $request['destination_latitude'],
            'longitude' => $request['destination_longitude']
        ];

        // Check if origin and destination range is in specified range else throw exception
        $this->validateWithinRange($promoCode, $originLatLong, $destinationLatLong);

        // Get Route and Corresponding Polylines from Google Map Direction API
        $route = $this->getRouteDirections($originLatLong, $destinationLatLong);

        // return the promo code data with route/polylines
        return [
            'routes' => $route['routes'],
            'promocode' => new PromocodeResource($promoCode),
        ];
    }

    /**
     * @param Promocode $promoCode
     * @param array $originLatLong
     * @param array $destinationLatLong
     * @throws PromoCodeRadiusRangeException
     */
    private function validateWithinRange(Promocode $promoCode, array $originLatLong, array $destinationLatLong) {
        if (!$promoCode->isWithinRange($originLatLong, $destinationLatLong)) {
            throw new PromoCodeRadiusRangeException();
        }
    }

    /**
     * @param array $originLatLong
     * @param array $destinationLatLong
     * @return mixed
     * @throws GoogleMapsDirectionAPIException
     */
    private function getRouteDirections(array $originLatLong, array $destinationLatLong) {
        try {
            $routeDirections = \GoogleMaps::load('directions')
                ->setParam([
                    'origin' => $originLatLong['latitude'] . ',' . $originLatLong['longitude'],
                    'destination' => $destinationLatLong['latitude'] . ',' . $destinationLatLong['longitude'],
                ])->get();

            return json_decode($routeDirections, true);
        } catch (\Exception $exception) {
            throw new GoogleMapsDirectionAPIException();
        }
    }

    /**
     * @return mixed
     * @throws \ReflectionException
     */
    public function activePromoCodes() {
        return $this->all(
            ['is_active' => true]
        );
    }

    /**
     * @return mixed
     * @throws \ReflectionException
     */
    public function inActivePromoCodes() {
        return $this->all(
            ['is_active' => false]
        );
    }

    /**
     * @param int $promocodeId
     * @return mixed
     * @throws \ReflectionException
     */
    public function activatePromoCode(int $promocodeId) {
        return $this->update($promocodeId, ['is_active' => true]);
    }

    /**
     * @param int $promocodeId
     * @return mixed
     * @throws \ReflectionException
     */
    public function deActivatePromoCode(int $promocodeId)
    {
        return $this->update($promocodeId, ['is_active' => false]);
    }
}
