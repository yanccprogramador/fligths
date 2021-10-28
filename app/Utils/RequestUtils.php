<?php

namespace App\Utils;


class RequestUtils
{
    //
    public function request($url,$method,$body=null,$header=null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        $curloptMethod=$this->validateMethod($method);
        curl_setopt($ch, $curloptMethod, true);
        if($body!=null){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        if($header!=null){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        $result=curl_exec($ch);
        curl_close($ch);

        return(json_decode($result, true));
    }
    private function validateMethod($method){
        switch(strtoupper($method)){
            case 'GET':
                return CURLOPT_HTTPGET;
            case 'POST':
                return CURLOPT_POST;
            case 'PUT':
                return CURLOPT_PUT;
            default:
                return CURLOPT_HTTPGET;
        }
    }
}
