<?php

namespace App\Http\Controllers;

use \App\Services\OfferService;

class OfferController extends Controller
{
    protected $offerService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    public function get($id)
    {
        return $this->offerService->get($id);
        // return OfferService::get($id);
        // return app(OfferService::class)->get($id);
    }
}
