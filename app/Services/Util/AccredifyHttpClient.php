<?php

namespace App\Services\Util;

use Illuminate\Support\Facades\Http;

class AccredifyHttpClient implements HttpClient
{

    public function get(string $endpoint, array $data = []): array
    {
        $response = Http::get($endpoint, $data);
        return [
            'httpResponseStatus' => $response->getStatusCode() ?? null,
            'httpResponseContent' => $response->getBody()->getContents() ?? null
        ];

    }

    public function post(string $endpoint, array $data): array
    {
        throw new \Exception("This HTTP Post method is not implemented.");
    }
}
