<?php

namespace Bitvavo\Interfaces;

interface API
{
    public function get(string $endpoint, array $params = []);
    public function post(string $endpoint, array $params = [], array $body = []);
}
