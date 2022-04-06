<?php

namespace App\Http\Controllers;

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

    public function create()
    {
        // show form to create a promocode
    }


    public function store(Request $request)
    {
        // store a new promocode
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
}
