<?php


namespace App\Http\Controllers;

use App\Constants\Constants;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;
use App\Console\Client as RequestClient;

class OfferController extends BaseController
{
    /**
     * Método que busca a lista inicial de
     * Televisores com seus preços
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $url = Constants::URL_INITIAL_TYPE;

        $crawler = RequestClient::request($url);

        //return response()->json(["data" => $crawler->text()]);

        $stringCountResult = $this->assembleArray($crawler);

        return response()->json(["urlRequest" => $url, "data" => $stringCountResult]);
    }

    /**
     * Método que busca a lista de
     * Televisores com seus preços
     * de acordo com a página
     * 1,2,3,4...
     * @return \Illuminate\Http\JsonResponse
     */
    public function lister(Request $request)
    {
        $page = $request->id == 0 ? 0 : ($request->id - 1) * 24;
        $url = Constants::URL_QUERY_TYPE.($page);
        $crawler = RequestClient::request($url);

        $stringCountResult = $this->assembleArray($crawler);

        return response()->json(["urlRequest" => $url, "data" => $stringCountResult]);
    }

    private function assembleArray($client) {

        return $client->filter('.jRHnRS')->each(function ($node) {
            $return = $node->filter('a')->each(function ($a) {
                $data['name'] = $a->filter('span.keKVYT')->each(function ($name) {
                    return $name->text();
                });

                if (count($data['name'])){
                    $data['name'] = $data['name'][0];
                }

                $data['priceIn'] = $a->filter('div.bVMOoi > span.izVeKJ')->each(function ($price) {
                    return str_replace( 'icone de primePrime', '', $price->text());
                });

                if (count($data['priceIn'])){
                    $data['priceIn'] = $data['priceIn'][0];
                }

                $data['pricePer'] = $a->filter('div.bVMOoi > span.kTMqhz')->each(function ($price) {
                    return str_replace( 'icone de primePrime', '', $price->text());
                });

                if (count($data['pricePer'])){
                    $data['pricePer'] = $data['pricePer'][0];
                }

                return $data;
            });

            return count($return) ? $return[0] : [];
        });
    }
}