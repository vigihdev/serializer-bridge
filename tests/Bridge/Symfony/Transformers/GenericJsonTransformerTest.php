<?php

declare(strict_types=1);

namespace Vigihdev\Serializer\Tests\Bridge\Symfony\Transformers;

use PHPUnit\Framework\Attributes\Test;
use Vigihdev\Serializer\Bridge\Symfony\Transformers\GenericJsonTransformer;
use Vigihdev\Serializer\Exception\SerializerException;
use Vigihdev\Serializer\Tests\TestCase;
use Vigihdev\Serializer\Tests\TestDto;
use Vigihdev\Serializer\Tests\TestDtoWithSerializedName;
use Vigihdev\Serializer\Tests\TestPostWithTagsDto;

final class GenericJsonTransformerTest extends TestCase
{
    private GenericJsonTransformer $transformer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transformer = new GenericJsonTransformer(TestDto::class);
    }

    #[Test]
    public function can_transform_json_to_object(): void
    {
        $json = '{"name": "John Doe", "age": 30, "email": "john@example.com"}';

        $result = $this->transformer->transformJson($json);

        $this->assertInstanceOf(TestDto::class, $result);
        $this->assertEquals('John Doe', $result->getName());
        $this->assertEquals(30, $result->getAge());
        $this->assertEquals('john@example.com', $result->getEmail());
    }

    #[Test]
    public function can_transform_json_with_serializedName_annotation(): void
    {
        $transformer = new GenericJsonTransformer(TestDtoWithSerializedName::class);
        $json = '{"first_name": "John", "last_name": "Doe", "user_email": "john.doe@example.com", "user_age": 30}';

        $result = $transformer->transformJson($json);

        $this->assertInstanceOf(TestDtoWithSerializedName::class, $result);
        $this->assertEquals('John', $result->firstName);
        $this->assertEquals('Doe', $result->lastName);
        $this->assertEquals('john.doe@example.com', $result->userEmail);
        $this->assertEquals(30, $result->userAge);
    }

    #[Test]
    public function can_transform_json_with_serializedName_from_file(): void
    {
        $transformer = new GenericJsonTransformer(TestDtoWithSerializedName::class);
        $filepath = __DIR__ . '/../../../fixtures/test_serializedName_data.json';

        $result = $transformer->transformWithFile($filepath);

        $this->assertInstanceOf(TestDtoWithSerializedName::class, $result);
        $this->assertEquals('John', $result->firstName);
        $this->assertEquals('Doe', $result->lastName);
        $this->assertEquals('john.doe@example.com', $result->userEmail);
        $this->assertEquals(30, $result->userAge);
    }

    #[Test]
    public function throws_exception_when_json_is_not_an_object_for_transformJson(): void
    {
        $json = '["item1", "item2"]'; // This is an array, not an object

        $this->expectException(SerializerException::class);
        $this->expectExceptionMessage('Json file must be an object');

        $this->transformer->transformJson($json);
    }

    #[Test]
    public function throws_exception_on_invalid_json_for_transformJson(): void
    {
        $json = '{invalid json}';

        $this->expectException(SerializerException::class);
        $this->expectExceptionMessage('Failed to deserialize');

        $this->transformer->transformJson($json);
    }

    #[Test]
    public function can_transform_json_array_to_array_of_objects(): void
    {
        $json = '[{"name": "John Doe", "age": 30, "email": "john@example.com"}, {"name": "Jane Smith", "age": 25, "email": "jane@example.com"}]';

        $result = $this->transformer->transformArrayJson($json);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertInstanceOf(TestDto::class, $result[0]);
        $this->assertInstanceOf(TestDto::class, $result[1]);
        $this->assertEquals('John Doe', $result[0]->getName());
        $this->assertEquals('Jane Smith', $result[1]->getName());
    }

    #[Test]
    public function can_transform_json_array_with_serializedName_annotation(): void
    {
        $transformer = new GenericJsonTransformer(TestDtoWithSerializedName::class);
        $json = '[{"first_name": "John", "last_name": "Doe", "user_email": "john.doe@example.com", "user_age": 30}, {"first_name": "Jane", "last_name": "Smith", "user_email": "jane.smith@example.com", "user_age": 25}]';

        $result = $transformer->transformArrayJson($json);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertInstanceOf(TestDtoWithSerializedName::class, $result[0]);
        $this->assertInstanceOf(TestDtoWithSerializedName::class, $result[1]);
        $this->assertEquals('John', $result[0]->firstName);
        $this->assertEquals('Jane', $result[1]->firstName);
        $this->assertEquals('Doe', $result[0]->lastName);
        $this->assertEquals('Smith', $result[1]->lastName);
    }

    #[Test]
    public function throws_exception_when_json_is_not_an_array_for_transformArrayJson(): void
    {
        $json = '{"name": "John Doe", "age": 30}'; // This is an object, not an array

        $this->expectException(SerializerException::class);
        $this->expectExceptionMessage('Json file must be an array');

        $this->transformer->transformArrayJson($json);
    }

    #[Test]
    public function throws_exception_on_invalid_json_for_transformArrayJson(): void
    {
        $json = '{invalid json}';

        $this->expectException(SerializerException::class);
        $this->expectExceptionMessage('Failed to deserialize');

        $this->transformer->transformArrayJson($json);
    }

    #[Test]
    public function can_transform_from_valid_json_file(): void
    {
        $filepath = __DIR__ . '/../../../fixtures/test_data.json';

        $result = $this->transformer->transformWithFile($filepath);

        $this->assertInstanceOf(TestDto::class, $result);
        $this->assertEquals('John Doe', $result->getName());
        $this->assertEquals(30, $result->getAge());
        $this->assertEquals('john@example.com', $result->getEmail());
    }

    #[Test]
    public function can_transform_from_valid_json_array_file(): void
    {
        $filepath = __DIR__ . '/../../../fixtures/test_array_data.json';

        $result = $this->transformer->transformWithFile($filepath);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertInstanceOf(TestDto::class, $result[0]);
        $this->assertInstanceOf(TestDto::class, $result[1]);
        $this->assertEquals('John Doe', $result[0]->getName());
        $this->assertEquals('Jane Smith', $result[1]->getName());
    }

    #[Test]
    public function throws_exception_when_file_does_not_exist(): void
    {
        $filepath = __DIR__ . '/../../../fixtures/nonexistent.json';

        $this->expectException(SerializerException::class);
        $this->expectExceptionMessage('Transform file error');

        $this->transformer->transformWithFile($filepath);
    }

    #[Test]
    public function throws_exception_when_file_contains_invalid_json(): void
    {
        $filepath = __DIR__ . '/../../../fixtures/invalid.json';

        // Create a temporary file with invalid JSON
        file_put_contents($filepath, '{invalid json}');

        $this->expectException(SerializerException::class);
        $this->expectExceptionMessage('Transform file error');

        $this->transformer->transformWithFile($filepath);

        // Clean up
        unlink($filepath);
    }

    #[Test]
    public function can_transform_json_array_with_doc_block_tags(): void
    {
        $json = '{"title": "Belajar Library Bridge", "tags": ["php", "serializer", "symfony"]}';
        $transformer = new GenericJsonTransformer(TestPostWithTagsDto::class);

        $result = $transformer->transformJson($json);

        $this->assertInstanceOf(TestPostWithTagsDto::class, $result);
        $this->assertEquals("Belajar Library Bridge", $result->getTitle());

        $this->assertIsArray($result->getTags());
        $this->assertCount(3, $result->getTags());
        $this->assertEquals("php", $result->getTags()[0]);
        $this->assertEquals("serializer", $result->getTags()[1]);
    }
}
