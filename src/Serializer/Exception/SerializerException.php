<?php

declare(strict_types=1);

namespace Vigihdev\Serializer\Exception;

final class SerializerException extends AbstractSerializerException
{

    public static function handleFromThrowable(\Throwable $e): static
    {
        return new static($e->getMessage(), $e->getCode(), $e);
    }

    public static function fileNotFound(string $filepath): static
    {
        return new static("File not found: {$filepath}");
    }

    public static function fileReadError(string $filepath): static
    {
        return new static("Cannot read file: {$filepath}");
    }

    public static function invalidJson(string $details): static
    {
        return new static("Invalid JSON format: {$details}");
    }

    public static function deserializationFailed(string $dtoClass, string $error): static
    {
        return new static("Failed to deserialize to {$dtoClass}: {$error}");
    }

    public static function classNotFound(string $className): static
    {
        return new static("Class not found: {$className}");
    }

    public static function invalidData(string $field, string $reason): static
    {
        return new static("Invalid data for field '{$field}': {$reason}");
    }

    public static function missingRequiredField(string $field): static
    {
        return new static("Missing required field: {$field}");
    }

    public static function renderFailed(string $rendererClass, string $error): static
    {
        return new static("Renderer {$rendererClass} failed: {$error}");
    }

    public static function transformFileError(string $error): static
    {
        return new static("Transform file error: {$error}");
    }
}
