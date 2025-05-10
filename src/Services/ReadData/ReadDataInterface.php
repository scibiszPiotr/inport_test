<?php

namespace Pscibisz\Inpost\Services\ReadData;

interface ReadDataInterface
{
    /** for the needs of a simple test task I use a file with order data,
     * the interface should enable, for example, the use of a controller with a form
     */
    public function get(): array;
}