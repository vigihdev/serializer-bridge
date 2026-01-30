<?php

declare(strict_types=1);

namespace Vigihdev\Serializer\Bridge\Symfony\Factory;

use Vigihdev\Serializer\Bridge\Symfony\Contracts\TransformerInterface;
use Vigihdev\Serializer\Bridge\Symfony\Transformers\GenericJsonTransformer;

final class JsonTransformerFactory
{

    public static function create(string $dtoClass): TransformerInterface
    {
        return new GenericJsonTransformer($dtoClass);
    }
}
