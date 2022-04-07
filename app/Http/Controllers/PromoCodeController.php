<?php

namespace App\Http\Controllers;

use App\Exceptions\GoogleMapsDirectionAPIException;
use App\Exceptions\PromoCodeDoesNotExistException;
use App\Exceptions\PromoCodeExpiredException;
use App\Exceptions\PromoCodeRadiusRangeException;
use App\Http\Requests\CheckValidPromoCodeRequest;
use App\Http\Requests\CreatePromocodeRequest;
use App\Http\Requests\UpdatePromoCodeRequest;
use App\Http\Resources\Promocode as PromocodeResource;
use App\Http\Resources\PromoCodeCollection;
use App\Models\Promocode;
use App\Repository\PromoCodeRepository;
use Illuminate\Http\JsonResponse;

class PromoCodeController extends Controller
{
    protected $promoCodesRepository;

    public function __construct(PromoCodeRepository $promoCodesRepository) {
        $this->promoCodesRepository = $promoCodesRepository;
    }

    /**
     * @return JsonResponse
     * @throws \ReflectionException
     */
    public function index() {
        // show all promocodes
        $allPromoCodes = $this->promoCodesRepository->all();

        return response()->json(
            [
                'status' => 'success',
                'data' => new PromoCodeCollection($allPromoCodes),
            ]
        );
    }

    public function create() {
        // show form to create a promocode
    }

    /**
     * @param CreatePromocodeRequest $request
     * @return JsonResponse
     */
    public function store(CreatePromoCodeRequest $request) {
        // store a new promocode
        $newPromoCode = $this->promoCodesRepository->create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'The Promo Code was successfully created...',
            'data' => new PromocodeResource($newPromoCode)
        ]);
    }

    /**
     * @param Promocode $promocode
     * @return JsonResponse|string[]
     */
    public function show(Promocode $promocode){
        //show a promo code
        $promoCode = $this->promoCodesRepository->find($promocode->id);

        return response()->json([
            'status' => 'success',
            'message' => 'The Promo Code has been found...',
            'promocode' => new PromocodeResource($promoCode)
        ]);
    }


    public function edit(Promocode $promoCode)
    {
        //show form to edit the post
    }


    /**
     * @param UpdatePromoCodeRequest $request
     * @param Promocode $promocode
     * @return array|JsonResponse
     */
    public function update(UpdatePromoCodeRequest $request, Promocode $promocode) {
        // save the edited promocode
        $promoCode = $this->promoCodesRepository->update($promocode->id, $request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'The Promo code has been successfully updated',
            'data' => new PromocodeResource($promoCode)
        ]);
    }

    /**
     * @param Promocode $promocode
     * @return JsonResponse|string[]
     */
    public function destroy(Promocode $promocode) {
        // delete a promo code
        $deletedPromoCode = $this->promoCodesRepository->destroy($promocode->id);

        return response()->json([
            'status' => 'success',
            'message' => 'The Promo code has been deleted successfully',
        ]);
    }

    /**
     * @param Promocode $promocode
     * @return JsonResponse|string[]
     */
    public function restore(Promocode $promocode) {
        // restore a deleted promo code
        $restoredPromoCode = $this->promoCodesRepository->restore($promocode->id);

        return response()->json([
            'status' => 'success',
            'message' => 'The Promo code has been restored successfully',
        ]);
    }

    /**
     * @param CheckValidPromoCodeRequest $request // $value->start_at
     * @return JsonResponse
     * @throws GoogleMapsDirectionAPIException
     * @throws PromoCodeRadiusRangeException
     * @throws PromoCodeExpiredException
     * @throws PromoCodeDoesNotExistException
     */
    public function checkvalid(CheckValidPromoCodeRequest $request) {
        $promoCode = $this->promoCodesRepository->checkvalid($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'The Promo Code is valid',
            'data' => $promoCode
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function activePromoCodes() {
        // get all the promo codes that are still active
        $promoCodes = $this->promoCodesRepository->activePromoCodes();
        // return response
        return response()->json(['status' => 'success', 'data' => new PromocodeCollection($promoCodes)]);
    }

    /**
     * @return JsonResponse
     */
    public function inActivePromoCodes() {
        // get all the promo codes that are in-active
        $promoCodes = $this->promoCodesRepository->inActivePromoCodes();
        // return response
        return response()->json(['status' => 'success', 'data' => new PromocodeCollection($promoCodes)]);
    }

    /**
     * @param Promocode $promocode
     * @return JsonResponse|string[]
     */
    public function activatePromoCode(Promocode $promocode)
    {
        $promocode = $this->promoCodesRepository->activatePromoCode($promocode->id);

        return response()->json([
            'status' => 'success',
            'message' => 'The Promo code has been successfully activated',
        ]);
    }

    /**
     * @param Promocode $promocode
     * @return JsonResponse|string[]
     */
    public function deActivatePromoCode(Promocode $promocode)
    {
        $promocode = $this->promoCodesRepository->deActivatePromoCode($promocode->id);

        return response()->json([
            'status' => 'success',
            'message' => 'The Promo code has been successfully de-activated',
        ]);
    }
}
