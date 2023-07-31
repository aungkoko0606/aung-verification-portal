<?php

namespace App\Services\Util;

interface HttpClient
{

    public function get(string $endpoint, array $data): array;

    public function post(string $endpoint, array $data): array;

}
