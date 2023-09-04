<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

require 'vendor/autoload.php';

class DocuSignAPIClient
{
    private $client = null;
    //const API_URL = "https://api.cloudways.com/api/v1";
    var $jwtToken;
    var $accessToken;

    public function __construct($jwt)
    {
        $this->jwtToken = $jwt;
        $this->client = new Client();
        $this->prepare_access_token();
    }

    public function prepare_access_token()
    {
//        $client = new Client();
//        $options = [
//            'multipart' => [
//                [
//                    'name' => 'grant_type',
//                    'contents' => 'urn:ietf:params:oauth:grant-type:jwt-bearer'
//                ],
//                [
//                    'name' => 'assertion',
//                    'contents' => 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiI4ZmYyM2Q4MS0wNDM5LTQ0M2UtYmE3ZS1jYzQwNTk3Yzc3YWIiLCJzdWIiOiJmZDQyN2JmNi1mNmFhLTRmMTUtYjFkZi1hNjJlZjE3ZjcyMTQiLCJhdWQiOiJhY2NvdW50LWQuZG9jdXNpZ24uY29tIiwiaWF0IjoxNTk4MzgzMTIzLCJleHAiOjE3OTM3Mzg0NzcsInNjb3BlIjoic2lnbmF0dXJlIGltcGVyc29uYXRpb24ifQ.wwUiNIxV6O1O9QFoB1IhXl4HZu4cs1_SqC5kzHuF8SXv0-xgiQwEJK36JjRPZjTvcTzVBRU4S5dGtCvd1iCVe2yxe5B0DBQukWYcgB7Xgcf0P96Mwf0HvOoIMPpqE9aqOR_iuG9p0Zq3JTLfgxhL_dedVPt61BM70Kc-Q7eqqRoWPtIhVZMFBKPO710gB39ey85Bk_LPiNLcYRIQFuBVkgfsEaEBimujiZ3-TDSYoGB2DCZJjug0aXB7fdv7pliMd0q6P-YvHoIDVu6ztkjRvpt93blTQl0A737oi6Md3fZxmZycti8b8ZVwBh7WVWm_EZDkVUJTyOjw1buiAuTUSQ'
//                ]
//            ]];
//        $request = new Request('POST', 'https://account-d.docusign.com/oauth/token');
//        $res = $client->sendAsync($request, $options)->wait();
//        $result = json_decode($res->getBody()->getContents());
//     //   return $result->access_token;
        try {
            // $url = self::API_URL . “/oauth/access_token”;
            $url = 'https://account-d.docusign.com/oauth/token';
        $options = [
            'multipart' => [
                [
                    'name' => 'grant_type',
                    'contents' => 'urn:ietf:params:oauth:grant-type:jwt-bearer'
                ],
                [
                    'name' => 'assertion',
                    'contents' => $this->jwtToken
                ]
            ]];
            $response = $this->client->post($url, $options);
            $result = json_decode($response->getBody()->getContents());
            $this->accessToken = $result->access_token;
        } catch (RequestException $e) {
            $response = $this->StatusCodeHandling($e);
            return $response;
        }
    }

    public function getUserInfo()
    {
        try {
            $url = "https://account-d.docusign.com/oauth/userinfo";
            $header = array('Authorization' => 'Bearer ' . $this->accessToken);
            $response = $this->client->get($url, array('headers' => $header));
            $result = $response->getBody()->getContents();
            return $result;
    } catch (RequestException $e) {
            $response = $this->StatusCodeHandling($e);
            return $response;
        }
    }

    public function StatusCodeHandling($e)
    {
        if ($e->getResponse()->getStatusCode() == '400') {
            $this->prepare_access_token();
        } elseif ($e->getResponse()->getStatusCode() == '422') {
            $response = json_decode($e->getResponse()->getBody(true)->getContents());
            return $response;
        } elseif ($e->getResponse()->getStatusCode() == '500') {
            $response = json_decode($e->getResponse()->getBody(true)->getContents());
            return $response;
        } elseif ($e->getResponse()->getStatusCode() == '401') {
            $response = json_decode($e->getResponse()->getBody(true)->getContents());
            return $response;
        } elseif ($e->getResponse()->getStatusCode() == '403') {
            $response = json_decode($e->getResponse()->getBody(true)->getContents());
            return $response;
        } else {
            $response = json_decode($e->getResponse()->getBody(true)->getContents());
            return $response;
        }
    }
}




