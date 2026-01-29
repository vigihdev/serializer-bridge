<?php

declare(strict_types=1);

namespace Vigihdev\Serializer\Exception;

final class SerializerException extends AbstractSerializerException
{

    public static function fileNotFound(string $filepath): static
    {
        return new self(
            message: sprintf("File %s not found", $filepath),
            context: [
                'filepath' => $filepath,
            ],
            code: 404,
            solutions: [
                'Check the filepath and make sure the file exists',
                'Create the file if it does not exist'
            ]
        );
    }
}
