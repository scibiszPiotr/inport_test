<?php

namespace Tests\HttpClient;

use JsonSerializable;

class DummyJson implements JsonSerializable
{
    public function jsonSerialize(): array
    {
        return ['dummy' => 'value'];
    }
}