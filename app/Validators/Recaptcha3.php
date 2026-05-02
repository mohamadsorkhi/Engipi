<?php
namespace App\Validators;
use App\Models\Setting;
use GuzzleHttp\Client;
class Recaptcha3
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        $client = new Client;
        $secret = Setting::getValue("google_recaptcha3_secret");
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' =>
                    [
                        'secret' => $secret,
                        'response' => $value
                    ]
            ]
        );
        $body = json_decode((string)$response->getBody());
//        dd($body,$body->success);
        return $body->success;
    }
}
