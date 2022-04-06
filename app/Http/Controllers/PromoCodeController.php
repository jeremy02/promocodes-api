<?php

namespace App\Http\Controllers;

use App\Models\Promocode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function index()
    {
        // show all promocodes
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
