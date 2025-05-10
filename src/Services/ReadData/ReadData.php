<?php

namespace Pscibisz\Inpost\Services\ReadData;

use Pscibisz\Inpost\Exceptions\ValidateJsonException;

readonly class ReadData implements ReadDataInterface
{
    public function __construct(public string $path)
    {
    }

    public function get(): array
    {
        try {
            return json_decode(
                file_get_contents(__DIR__ . '/../../../data/' . $this->path),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (\JsonException $e) {
            throw new ValidateJsonException('Invalid JSON from file ' . $this->path);
        }
    }
}
