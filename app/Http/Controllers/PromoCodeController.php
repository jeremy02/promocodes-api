<?php

namespace App\Http\Controllers;

use App\Exceptions\GoogleMapsDirectionAPIException;
use App\Exceptions\PromoCodeRadiusRangeException;
use App\Http\Requests\CheckValidPromoCodeRequest;
use App\Http\Requests\CreatePromocodeRequest;
use App\Http\Resources\Promocode as PromocodeResource;
use App\Http\Resources\PromoCodeCollection;
use App\Models\Promocode;
use App\Repository\PromoCodeRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function show(Promocode $promoCode)
    {
        //show a promocode
    }


    public function edit(Promocode $promoCode)
    {
        //show form to edit the post
    }


    public function update(Request $request, Promocode $promoCode)
    {
        //save the edited promocode
    }


    public function destroy(Promocode $promoCode)
    {
        //delete a promocode
    }


    /**
     * @param CheckValidPromoCodeRequest $request // $value->start_at
     * @return JsonResponse
     * @throws GoogleMapsDirectionAPIException
     * @throws PromoCodeRadiusRangeException
     */
    public function checkvalid(CheckValidPromoCodeRequest $request) {
        $promoCode = $this->promoCodesRepository->checkvalid($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'The Promo Code is valid',
            'data' => $promoCode
        ]);
    }
}
