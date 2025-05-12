<?php

namespace Pscibisz\Inpost\Services\Serializer;

use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

/** In order not to configure the serializer in different places, I created a helper class that returns the symfony serializer */
class Serializer
{
    public SymfonySerializer $serializer;

    public function __construct()
    {
        /** serializer configuration to prepare DTO from array */
        $phpDocExtractor = new PhpDocExtractor();
        $reflectionExtractor = new ReflectionExtractor();

        $normalizers = [
            new ParcelCollectionDenormalizer(),
            new BackedEnumNormalizer(),
            new ArrayDenormalizer(),
            new ObjectNormalizer(
                null,
                new CamelCaseToSnakeCaseNameConverter(),
                new PropertyAccessor(),
                new PropertyInfoExtractor(
                    [],
                    [$phpDocExtractor, $reflectionExtractor],
                    [],
                    [$reflectionExtractor],
                    [$reflectionExtractor]
                )
            ),
        ];

        $encoders = [new JsonEncoder()];

        $this->serializer = new SymfonySerializer($normalizers, $encoders);
    }
}