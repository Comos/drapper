# Comos Drapper

Drapper means Data-Wrapper.

The purpose of the project is to simplify PHP data accessing.

For example, we have an json file named alex.json like this.
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
Fortunately, Drapper brings the change.

```
use Comos\Drapper\Loader;
$gender = Loader::fromJsonFile('alex.json')->str('gender', $defaultValue = 'private');
```

Only two lines. Actually, you can use the full qualified class name to compress the codes to one line if you like.

That's a one of scenarios those could satisfied by Drapper.

Not only files, but also an array that we can wrapper directly.
```php
use Comos\Drapper\Bean
$data = ['id'=>3, 'name'=>'alex'];
Bean::fromArray($data)->int('id');