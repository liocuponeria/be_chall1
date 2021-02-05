<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function healthz()
    {
        return response()->json(["retcode" => "SUCCESS"]);
    }
}
