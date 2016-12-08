# Comos Drapper

[![Build Status](https://secure.travis-ci.org/Comos/drapper.png)](http://travis-ci.org/Comos/drapper)

## Introduction

Defensive programming is a good and important practice.
It demands people to concern about input validations, data
type conversations and error handling. It's not easy.
Sometimes, it makes programs elephantine.
Drapper means Data-Wrapper. Its purpose is to
simplify PHP data accessing but strength the robust.
It could be figured out by following scenarios.

## Scenarios


## Loading data from JSON file

For example, we have a json file named alex.json like this.
```json
{
"id":1,
"name":"Alex",
"gender":null
}
```

We'd like read the file and access the `gender` field.

A matured phper would like to write like this.

```php
$fileContents = file_get_contents('alex.json');
if (!fileContents === false) {
    //report the error
}
$data = json_decode($fileContents, true);
if (!is_array($data)) {
    //report the error
}
$gender = isset($data['gender']) ? $data['gender'] : 'private';
```

Finally, we get the gender, But it's not enough yet.
We have to test the data type.

```
if (!is_string($gender) {
    // report the error
}
```

......

It's elephantine.
If we use Drapper...

```
use Comos\Drapper\Loader;
$gender = Loader::fromJsonFile('alex.json')->str('gender', $defaultValue = 'private');
```

Only two lines. Even more you can use the full qualified class name to compress
the codes to one line if you like.

In the scenario, Drapper checks existence, converts data type and handles defaulting strategy.
If something is out of the fault-tolerant protocols, an exception would be thrown.


That's a one of scenarios. Actually, Drapper can do more.

### To wrapper an array directly

```php
use Comos\Drapper\Bean;
$data = ['id'=>3, 'name'=>'alex'];
Bean::fromArray($data)->int('id');
```

### Reading field with default value

```php
use Comos\Drapper\Bean;

$defaultValue = 0.1;
$data = ['r0' => 0.2];
$bean = Bean::fromArray($data);
$r0 = $bean->float('r0', '');
```

## Integration

Drapper is easy to be integrated to your applications
 or libraries because of following reasons:

1. Build with Composer.
2. Follows the PSR-4.
3. Lightweight, has no more dependencies.

We recommend you to use Composer.
That's the easiest way to integrate with drapper.

```bash
composer require comos/drapper
```

## More informations
See [https://github.com/Comos/drapper/wiki](Wiki)

Or you could deploy Drapper to your include path.
 Then register a PSR-4 autoload callback to your application.
 See [PHP-FIG](http://php-fig.org).
