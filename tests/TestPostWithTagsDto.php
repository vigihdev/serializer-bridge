<?php

namespace Vigihdev\Serializer\Tests;

class TestPostWithTagsDto
{
    /** @var string[] */
    private array $tags;

    private string $title;

    public function __construct(array $tags, string $title)
    {
        $this->tags = $tags;
        $this->title = $title;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
