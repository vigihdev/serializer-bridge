<?php

declare(strict_types=1);

namespace Vigihdev\Serializer\Bridge\Symfony\Transformers;

use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Vigihdev\Serializer\Bridge\Symfony\Contracts\TransformerInterface;
use Vigihdev\Serializer\Exception\SerializerException;

abstract class AbstractJsonTransformer implements TransformerInterface
{

    protected readonly Serializer $serializer;

    public function __construct()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader());
        $nameConverter = new MetadataAwareNameConverter($classMetadataFactory);
        $encoders = [new JsonEncoder()];
        $normalizers = [
            new ObjectNormalizer(
                $classMetadataFactory,
                $nameConverter,
                new PropertyAccessor(),
                new PhpDocExtractor()
            ),
            new ArrayDenormalizer(),
        ];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    protected function isValidFileJson(string $filepath): bool
    {

        if (!file_exists($filepath)) {
            throw SerializerException::fileNotFound($filepath);
        }

        if (!is_readable($filepath)) {
            throw SerializerException::fileReadError($filepath);
        }

        $json = file_get_contents($filepath);
        if ($json === false) {
            throw SerializerException::fileReadError($filepath);
        }

        json_decode($json);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw SerializerException::invalidJson(json_last_error_msg());
        }
        return true;
    }

    protected function transformFromFile(string $filepath): object|array
    {

        try {
            $this->isValidFileJson($filepath);
            $json = file_get_contents($filepath);
            if (is_array(json_decode($json))) {
                return $this->transformArrayJson($json);
            }
            return $this->transformJson($json);
        } catch (\Throwable $e) {
            throw SerializerException::handleFromThrowable($e);
        }
    }

    protected function transformFileAsObject(string $filepath): ?object
    {

        try {
            $this->isValidFileJson($filepath);
            $json = file_get_contents($filepath);
            if (!is_object(json_decode($json))) {
                throw SerializerException::invalidJson("Json file must be an object: {$filepath}");
            }
            return $this->transformJson($json);
        } catch (\Throwable $e) {
            throw SerializerException::handleFromThrowable($e);
        }
    }

    protected function transformFileAsArray(string $filepath): array
    {

        try {
            $this->isValidFileJson($filepath);
            $json = file_get_contents($filepath);
            if (!is_array(json_decode($json))) {
                throw SerializerException::invalidJson("Json file must be an array: {$filepath}");
            }
            return $this->transformArrayJson($json);
        } catch (\Throwable $e) {
            throw SerializerException::handleFromThrowable($e);
        }
    }
}
