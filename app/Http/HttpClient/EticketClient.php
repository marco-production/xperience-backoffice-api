<?php

namespace App\Http\HttpClient;

use Illuminate\Support\Facades\Http;

class EticketClient
{
    private $baseApiUrl = 'https://359b-179-51-78-185.ngrok.io/api/EticketsApp';
    private $apiKey = 'tanDtZX6FYzUh9gSY7UgyHX6pHTKmtz9Q76iFy9NLMvuFGXaxXrGX4MqjQu';

    /**
     * Get the ApiKey from E-ticket API.
     *
     * @return Http Response
     */
    public function getApplicationId()
    {
        $response = Http::withOptions([
            'verify' => true,
        ])->get($this->baseApiUrl.'/request-travel', ['key' => $this->apiKey]);

        $body = json_decode($response->body());
        return $response->successful() ? $body : ['errors' => $body];
    }

    /**
     * Post to Step One from E-ticket API 
     * Save general information
     *
     * @param  String $token, array $body
     * @return Http Response
     */
    public function eticketStepOne(array $body)
    {
        $response = Http::withOptions([
            'verify' => true,
        ])->post($this->baseApiUrl.'/step-one?key='.$this->apiKey, $body);

        $body = json_decode($response->body());
        return $response->successful() ? $body : ['errors' => $body];
    }

    /**
     * Post the Step Two from E-ticket API.
     * Save general traveler information
     * 
     * @param  String $token, array $body
     * @return Http Response
     */
    public function eticketStepTwo(array $body)
    {
        $response = Http::withOptions([
            'verify' => true,
        ])->post($this->baseApiUrl.'/step-two?key='.$this->apiKey, $body);

        $body = json_decode($response->body());

        if($response->successful()){

            if(isset($body->qrcode) || (isset($body->message) && $body->message == "Next Person")){

                return $body;

            } else if(isset($body->stepThreeInfo)) {
                $migratoryInformationId = [];
                $payloads = $body->stepThreeInfo;

                foreach ($payloads as $payload) {
                    array_push($migratoryInformationId, ["migratoryInformationId" => $payload->migratoryInformationId, "id" => $payload->customId]);
                }

                return $migratoryInformationId;
            }
        }
        return $body;
    }

    /**
     * Post the Step Three from E-ticket API.
     * Save the customs information of travelers
     * 
     * @param  String $token, array $body
     * @return Http Response
     */
    public function eticketStepThree(array $body)
    {
        $response = Http::withOptions([
            'verify' => true,
        ])->post($this->baseApiUrl.'/step-three?key='.$this->apiKey, $body);

        $body = json_decode($response->body());
        
        if($response->successful()){

            if((isset($body->message) && $body->message == 'Next Person')){

                return $body;

            } else if(isset($body->publicHealthInfo)) {

                $publicHealthId = [];
                $publicHealthInfo = $body->publicHealthInfo;

                foreach ($publicHealthInfo as $healthInfo) {
                    array_push($publicHealthId, ["migratoryInformationId" => $healthInfo->migratoryInformationId, "publicHealthId" => $healthInfo->publicHealthId]);
                }

                return $publicHealthId;
            }
        }
        return $body;
    }

    /**
     * Post the Step Three from E-ticket API.
     * Save the public health information of travelers
     * 
     * @param  String $token, array $body
     * @return Http Response
     */
    public function eticketStepFour(array $body)
    {
        $response = Http::withOptions([
            'verify' => true,
        ])->post($this->baseApiUrl.'/public-health?key='.$this->apiKey, $body);

        $body = json_decode($response->body());

        if($response->successful()){
            if(isset($body->qrcode) || (isset($body->message) && $body->message == "Next Person")){
                return $body;
            }
        }

        return $body;
    }
}
