<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Exception;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController{
    use ApiResponser;

    /**
     * @throws \JsonException
     * @throws Exception
     */
    protected function fetch(string $endpoint) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => getenv('RESTAURANTS_API_URL') . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        if(!$response) {
            throw new Exception('Can\'t connect to RESTAURANT API');
        }

        if(curl_getinfo($curl, CURLINFO_HTTP_CODE) !== 200) {
            throw new Exception('RESTAURANT API returned error');
        }

        return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    }
}
