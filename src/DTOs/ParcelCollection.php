<?php

namespace Pscibisz\Inpost\DTOs;

use ArrayIterator;
use Countable;
use IteratorAggregate;

final class ParcelCollection implements Countable, IteratorAggregate, \JsonSerializable
{
    private array $parcels = [];

    public function __construct(array $parcels = [])
    {
        $this->parcels = $parcels;
    }

    public function add(ParcelDto $parcel): void
    {
        $this->parcels[] = $parcel;
    }

    public function remove(ParcelDto $parcel): void
    {
        $this->parcels = array_filter($this->parcels, fn($p) => $p !== $parcel);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->parcels);
    }

    public function count(): int
    {
        return count($this->parcels);
    }

    public function jsonSerialize(): array
    {
        return array_map(fn($parcel) => $parcel->jsonSerialize(), $this->parcels);
    }

    public function toArray(): array
    {
        return $this->parcels;
    }
}