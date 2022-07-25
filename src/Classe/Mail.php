<?php

namespace App\Classe;
use Mailjet\{Client, Resources};

class Mail{

    private $api_key = '3b75bcc1e0589dc65f5c5150e1f70660';
    private $api_key_secret = '185c2afd28cba22ba47a5a762a39dcb4';

    public function send($to_email,$to_name,$subject,$content){
        $mj=new Client($this->api_key,$this->api_key_secret, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "helalajili94@gmail.com",
                        'Name' => "Healthy Nutrition"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 3406469,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content'=>$content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();

    }
}