<?php

declare(strict_types=1);

namespace Vigihdev\Serializer\Exception;

use Exception;

abstract class AbstractSerializerException extends Exception implements SerializerExceptionInterface
{

    /**
     * Additional context data
     */
    protected array $context = [];

    /**
     * Suggested solutions
     */
    protected array $solutions = [];

    public function __construct(
        string $message,
        int $code = 0,
        ?\Throwable $previous = null,
        array $context = [],
        array $solutions = []
    ) {
        $this->context = $context;
        $this->solutions = $solutions;

        parent::__construct($message, $code, $previous);
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function getSolutions(): array
    {
        return $this->solutions;
    }

    public function getFormattedMessage(): string
    {
        $message = $this->getMessage();

        if (!empty($this->context)) {
            $contextStr = json_encode($this->context, JSON_UNESCAPED_SLASHES);
            $message .= " (context: {$contextStr})";
        }
        return $message;
    }

    /**
     * Convert to array for logging/API responses
     */
    public function toArray(): array
    {
        return [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'context' => $this->context,
            'solutions' => $this->solutions,
            'exception' => static::class,
        ];
    }
}
