# 🛠️ Serializer Bridge

![Tests](https://github.com/vigihdev/serializer-bridge/actions/workflows/tests.yml/badge.svg)
![Push](https://github.com/vigihdev/serializer-bridge/actions/workflows/push.yml/badge.svg)
![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

A collection of DTOs and Contracts of serializer bridge for PHP.

---

### Basic DTO

```php
class UserDto
{
    public function __construct(
        public readonly string $name,
        public readonly int $age,
        public readonly string $email
    ) {}
}
```

### Transform from File

```php
$transformer = JsonTransformerFactory::create(UserDto::class);
$users = $transformer->transformWithFile('users.json');

foreach ($users as $user) {
    echo $user->name . ': ' . $user->email;
}
```

### Transform from JSON String

```php
$json = '{"name": "Alice", "age": 25, "email": "alice@example.com"}';
$user = $transformer->transformJson($json);

echo $user->name; // "Alice"
```

## Error Handling

```php
use Vigihdev\Serializer\Exception\SerializerException;

try {
    $transformer->transformWithFile('invalid.json');
} catch (SerializerException $e) {
    echo "Error: " . $e->getMessage();
}
```
