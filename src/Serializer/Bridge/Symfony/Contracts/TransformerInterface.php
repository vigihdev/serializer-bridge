<?php

declare(strict_types=1);

namespace Vigihdev\Serializer\Bridge\Symfony\Contracts;

interface TransformerInterface
{
    /**
     * Transform JSON file to object or array of objects
     */
    public function transformWithFile(string $filepath): object|array;

    /**
     * Transform JSON string to single object
     */
    public function transformJson(string $json): object;

    /**
     * Transform JSON string to array of objects  
     */
    public function transformArrayJson(string $json): array;
}
