<?php

namespace Pscibisz\Inpost\Services\Serializer;

use Pscibisz\Inpost\DTOs\DimensionsDto;
use Pscibisz\Inpost\DTOs\ParcelCollection;
use Pscibisz\Inpost\DTOs\ParcelDto;
use Pscibisz\Inpost\DTOs\WeightDto;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ParcelCollectionDenormalizer implements DenormalizerInterface
{
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === ParcelCollection::class;
    }

    public function denormalize($data, $type, $format = null, array $context = []): ParcelCollection
    {
        $parcels = array_map(fn ($item) => new ParcelDto(
            $item['id'],
            new DimensionsDto(
                $item['dimensions']['length'],
                $item['dimensions']['width'],
                $item['dimensions']['height'],
                $item['dimensions']['unit']
            ),
            new WeightDto(
                $item['weight']['amount'],
                $item['weight']['unit']
            ),
            $item['is_non_standard']
        ), $data);

        return new ParcelCollection($parcels);
    }

    public function getSupportedTypes(?string $format): array
    {
        return ['object' => true];
    }
}
