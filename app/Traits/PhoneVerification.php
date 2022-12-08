<?php

namespace App\Traits;

use Twilio\Rest\Client;


/**
 * Verify phone number of registered User
 */
trait PhoneVerification {

    public function sendSMSTo($phone_number)
    {
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");

        $twilio = new Client($twilio_sid, $token);
        $twilio->verify->v2->services($twilio_verify_sid)->verifications->create($phone_number, "sms");
    }

    public function verifySMSCode($phone_number, $verification_code)
    {
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        
        $twilio = new Client($twilio_sid, $token);
        $verification = $twilio->verify->v2->services($twilio_verify_sid)->verificationChecks;
        return $verification->create(["to" => $phone_number, "code" => $verification_code]);
    }
}