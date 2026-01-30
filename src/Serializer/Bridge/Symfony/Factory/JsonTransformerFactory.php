<?php

declare(strict_types=1);

namespace Vigihdev\Serializer\Bridge\Symfony\Factory;

use Vigihdev\Serializer\Bridge\Symfony\Contracts\TransformerInterface;
use Vigihdev\Serializer\Bridge\Symfony\Transformers\GenericJsonTransformer;
use Vigihdev\Serializer\Exception\SerializerException;

final class JsonTransformerFactory
{

    public static function create(string $dtoClass): TransformerInterface
    {
        if (!class_exists($dtoClass)) {
            throw new SerializerException(sprintf('Class "%s" does not exist', $dtoClass));
        }
        return new GenericJsonTransformer($dtoClass);
    }
}
