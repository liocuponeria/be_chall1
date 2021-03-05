<?php

namespace App\Http\Controllers;

use App\Services\CralwerService;

class OfferController extends Controller
{
    protected $offerService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CralwerService $offerService)
    {
        $this->offerService = $offerService;
    }

    public function get($page)
    {        
        $result = $this->offerService->getPageData($page);
        return response()->json(['data'=>$result],200);
    }
}
