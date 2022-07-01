# Валидация данных полученных запросом

![Package version](https://img.shields.io/github/v/release/motokraft/validator)
![Total Downloads](https://img.shields.io/packagist/dt/motokraft/validator)
![PHP Version](https://img.shields.io/packagist/php-v/motokraft/validator)
![Repository Size](https://img.shields.io/github/repo-size/motokraft/validator)
![License](https://img.shields.io/packagist/l/motokraft/validator)

## Установка

Библиотека устанавливается с помощью пакетного менеджера [**Composer**](https://getcomposer.org/)

Добавьте библиотеку в файл `composer.json` вашего проекта:

```json
{
    "require": {
        "motokraft/validator": "^1.0"
    }
}
```

или выполните команду в терминале

```bash
$ php composer require motokraft/validator
```

Включите автозагрузчик Composer в код проекта:

```php
require __DIR__ . '/vendor/autoload.php';
```

## Примеры инициализации

```php
use \Motokraft\Validator\Validator;

// Данные запроса
$input_data = [
    'name' => 'user-name',
    'email' => 'user@user.user'
];

// 1 вариант
$validator = new Validator($input_data);

// 2 вариант
$validator = Validator::getInstance('test');
$validator->loadArray($input_data);
```

## Лицензия

Эта библиотека находится под лицензией MIT License.