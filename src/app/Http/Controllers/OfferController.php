<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

class OfferController extends BaseController
{
    /**
     * Método que busca a lista inicial de
     * Televisores com seus preços
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $url = "https://www.submarino.com.br/busca/tv";
        $client = new Client(HttpClient::create(['timeout' => 60]));
        $client->setServerParameter(
            'HTTP_USER_AGENT',
            'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0'
        );

        $crawler = $client->request('GET', $url);

        //return response()->json(["data" => $crawler->text()]);

        $stringCountResult = $crawler->filter('.jRHnRS')->each(function ($node) {
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
        $page = $request->id * 24;
        $url = "https://www.submarino.com.br/busca/tv".($page == 0 ? "" : "?limite=24&offset=".$page);
        $client = new Client(HttpClient::create(['timeout' => 60]));
        $client->setServerParameter(
            'HTTP_USER_AGENT',
            'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0'
        );

        $crawler = $client->request('GET', $url);

        //return response()->json(["urlRequest" => $url, "data" => $crawler->text()]);

        $stringCountResult = $crawler->filter('.jRHnRS')->each(function ($node) {
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

        return response()->json(["urlRequest" => $url, "data" => $stringCountResult]);
    }
}