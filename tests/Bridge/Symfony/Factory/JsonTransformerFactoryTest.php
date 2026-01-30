<?php

declare(strict_types=1);

namespace Vigihdev\Serializer\Tests\Bridge\Symfony\Factory;

use PHPUnit\Framework\Attributes\Test;
use Vigihdev\Serializer\Bridge\Symfony\Contracts\TransformerInterface;
use Vigihdev\Serializer\Bridge\Symfony\Factory\JsonTransformerFactory;
use Vigihdev\Serializer\Bridge\Symfony\Transformers\GenericJsonTransformer;
use Vigihdev\Serializer\Exception\SerializerException;
use Vigihdev\Serializer\Tests\TestCase;
use Vigihdev\Serializer\Tests\TestDto;
use Vigihdev\Serializer\Tests\TestDtoWithSerializedName;

final class JsonTransformerFactoryTest extends TestCase
{
    #[Test]
    public function creates_transformer_instance_with_valid_class(): void
    {
        $transformer = JsonTransformerFactory::create(TestDto::class);

        $this->assertInstanceOf(TransformerInterface::class, $transformer);
        $this->assertInstanceOf(GenericJsonTransformer::class, $transformer);

        // Check that the transformer was created with the correct DTO class
        $reflection = new \ReflectionClass($transformer);
        $dtoClassProperty = $reflection->getProperty('dtoClass');
        $dtoClassProperty->setAccessible(true);

        $this->assertEquals(TestDto::class, $dtoClassProperty->getValue($transformer));
    }

    #[Test]
    public function creates_transformer_instance_with_serializedName_class(): void
    {
        $transformer = JsonTransformerFactory::create(TestDtoWithSerializedName::class);

        $this->assertInstanceOf(TransformerInterface::class, $transformer);
        $this->assertInstanceOf(GenericJsonTransformer::class, $transformer);

        // Check that the transformer was created with the correct DTO class
        $reflection = new \ReflectionClass($transformer);
        $dtoClassProperty = $reflection->getProperty('dtoClass');
        $dtoClassProperty->setAccessible(true);

        $this->assertEquals(TestDtoWithSerializedName::class, $dtoClassProperty->getValue($transformer));
    }

    #[Test]
    public function throws_exception_when_class_does_not_exist(): void
    {
        $nonExistentClass = 'NonExistent\\Class\\Path';

        $this->expectException(SerializerException::class);
        $this->expectExceptionMessage(sprintf('Class "%s" does not exist', $nonExistentClass));

        JsonTransformerFactory::create($nonExistentClass);
    }

    #[Test]
    public function can_create_transformer_and_use_it_successfully(): void
    {
        $transformer = JsonTransformerFactory::create(TestDto::class);

        $json = '{"name": "John Doe", "age": 30, "email": "john@example.com"}';
        $result = $transformer->transformJson($json);

        $this->assertInstanceOf(TestDto::class, $result);
        $this->assertEquals('John Doe', $result->getName());
        $this->assertEquals(30, $result->getAge());
        $this->assertEquals('john@example.com', $result->getEmail());
    }

    #[Test]
    public function can_create_transformer_with_serializedName_and_use_it_successfully(): void
    {
        $transformer = JsonTransformerFactory::create(TestDtoWithSerializedName::class);

        $json = '{"first_name": "John", "last_name": "Doe", "user_email": "john.doe@example.com", "user_age": 30}';
        $result = $transformer->transformJson($json);

        $this->assertInstanceOf(TestDtoWithSerializedName::class, $result);
        $this->assertEquals('John', $result->firstName);
        $this->assertEquals('Doe', $result->lastName);
        $this->assertEquals('john.doe@example.com', $result->userEmail);
        $this->assertEquals(30, $result->userAge);
    }
}