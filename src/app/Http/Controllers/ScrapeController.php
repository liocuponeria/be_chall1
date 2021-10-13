<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client as Goutte;
use Symfony\Component\HttpClient\HttpClient;

class ScrapeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private function crawler($client) {

        return $client->filter('.jRHnRS')->each(function ($item) {
            $return = $item->filter('a')->each(function ($a) {

                $data['name'] = $a->filter('h3.dBpyfL')
                    ->each(function ($name) {
                        return $name->text();
                    });
                if (count($data['name'])){
                    $data['name'] = $data['name'][0];
                }

                $data['price'] = $a->filter('span.kTMqhz')
                    ->each(function ($price) {
                        return str_replace( 'icone de primePrime', '', $price->text());
                    });
                if (count($data['price'])){
                    $data['price'] = $data['price'][0];
                }

                return $data;
            });

            return count($return) ? $return[0] : [];
        });
    }

    public function show(Request $request)
    {

        $url = 'https://www.submarino.com.br/busca/tv';

        if($request->id >= 1) {
            $page = $request->id == 0 ? 0 : ($request->id - 1) * 24;
            $limite = 24;
            $url = $url. '?limite='. $limite. '&offset=' . ($page);
        }

        $client = new Goutte(HttpClient::create(['timeout' => 60]));
        $client->setServerParameter(
            'HTTP_USER_AGENT', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0'
        );

        $crawler = $client->request('GET', $url);
        $ResultCrawler = $this->crawler($crawler);

        return response()->json(["data" => $ResultCrawler]);

    }

}
