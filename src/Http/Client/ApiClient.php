<?php


namespace Decole\Quasar\Http\Client;


use Decole\Quasar\Dto\ConfigDto;
use Decole\Quasar\Exception\ApiException;

class ApiClient
{
    public const GET = 'GET';
    public const POST = 'POST';
    public const PUT = 'PUT';
    public const DELETE = 'DELETE';

    private string $urlIot = 'https://yandex.ru/quasar/iot';

    private string $cookies;

    public function __construct(ConfigDto $config)
    {
        $this->cookies = $config->cookie;
    }

    public function request($url, $method = self::GET, array $params = [])
    {
        $header = [];
        $header[] = "Cookie: {$this->cookies}";

        $YaCurl = curl_init();
        curl_setopt($YaCurl, CURLOPT_URL, $url);

        if ($method === self::GET) {
            curl_setopt($YaCurl, CURLOPT_POST, false);
        } else {
            $header[] = 'x-csrf-token: ' . $this->getCsrfToken();
            $header[] = 'Access-Control-Allow-Credentials: true';
            $header[] = 'Access-Control-Allow-Origin: https://yandex.ru';
            $header[] = 'Content-type: application/json; charset=utf-8';

            if ($method !== self::POST) {
                curl_setopt($YaCurl, CURLOPT_CUSTOMREQUEST, $method);
            } else {
                curl_setopt($YaCurl, CURLOPT_POST, true);
            }

            curl_setopt($YaCurl, CURLOPT_POSTFIELDS, json_encode($params));
        }

        curl_setopt($YaCurl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($YaCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($YaCurl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($YaCurl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($YaCurl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($YaCurl, CURLOPT_ENCODING, 'gzip');
        curl_setopt($YaCurl, CURLOPT_VERBOSE, false);

        $result = curl_exec($YaCurl);

        if ($result == "Unauthorized\n") {
            throw new ApiException('Unauthorized by Quasar Yandex api');
        }

        return json_decode($result, true);
    }

    public function getCsrfToken(): string
    {
        $YaCurl = curl_init();

        curl_setopt($YaCurl, CURLOPT_URL, $this->urlIot);
        curl_setopt($YaCurl, CURLOPT_HEADER, false);
        curl_setopt($YaCurl, CURLOPT_HTTPHEADER, array("Cookie: {$this->cookies}"));
        curl_setopt($YaCurl, CURLOPT_POST, false);
        curl_setopt($YaCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($YaCurl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($YaCurl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($YaCurl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($YaCurl, CURLOPT_ENCODING, 'gzip');
        curl_setopt($YaCurl, CURLOPT_VERBOSE, false);

        curl_setopt($YaCurl, CURLOPT_FOLLOWLOCATION, 1);

        $result = curl_exec($YaCurl);
        curl_close($YaCurl);

        if (preg_match('/"csrfToken2":"(.+?)"/', $result, $m)) {
            $csrfToken = $m[1];
        } else {
            throw new ApiException('Not get csrf Token by Quasar API');
        }

        return $csrfToken;
    }
}
