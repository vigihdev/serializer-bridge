<?php

declare(strict_types=1);

namespace Vigihdev\Serializer\Bridge\Symfony\Transformers;

use Vigihdev\Serializer\Exception\SerializerException;

final class GenericJsonTransformer extends AbstractJsonTransformer
{

    public function __construct(
        private readonly string $dtoClass
    ) {
        parent::__construct();
    }

    public function transformJson(string $json): object
    {
        try {
            if (!is_object(json_decode($json))) {
                throw SerializerException::invalidJson("Json file must be an object: {$json}");
            }
            return $this->serializer->deserialize($json, $this->dtoClass, 'json');
        } catch (\Throwable $e) {
            throw SerializerException::deserializationFailed($this->dtoClass, $e->getMessage());
        }
    }

    public function transformArrayJson(string $json): array
    {
        try {
            if (!is_array(json_decode($json))) {
                throw SerializerException::invalidJson("Json file must be an array: {$json}");
            }
            return $this->serializer->deserialize($json, $this->dtoClass . '[]', 'json');
        } catch (\Throwable $e) {
            throw SerializerException::deserializationFailed($this->dtoClass . '[]', $e->getMessage());
        }
    }

    public function transformWithFile(string $filepath): object|array
    {
        try {
            $this->isValidFileJson($filepath);
            return $this->transformFromFile($filepath);
        } catch (\Throwable $e) {
            throw SerializerException::transformFileError($e->getMessage());
        }
    }
}
