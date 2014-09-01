# SimpleCurl for Laravel 4

A very simple an minimalistic [cURL](http://php.net/manual/book.curl.php) wrapper for [Laravel 4](http://laravel.com).

## Why?

```php
$response = Curl::get('http://example.com/data.json');
```

There are a lot of great php HTTP clients out there in the wild. But sometimes you don't want/need the overhead of a full-featured client which forces you to write 15 lines of code to load a RSS feed or retrive data from an REST API.

SimpleCurl is the easy one-liner for this purpose. It's not intended to become the next [guzzle](https://github.com/guzzle/guzzle), nor will it replace the [Zend Http Client](http://framework.zend.com/manual/2.0/en/modules/zend.http.client.html). ;)

## Install

**Composer:**

Add `passioncoder/simplecurl` to the `require` section of your `composer.json`:

```json
"require": {
    "passioncoder/simplecurl": "dev-master"
}
```

**Laravel:**

Add the service provider to your `app/config/app.php`:

```php
'providers' => array(

    'Passioncoder\SimpleCurl\ServiceProvider',
),
```

Add the alias to your `app/config/app.php` (optional):

```php
'aliases' => array(

    'Curl'            => 'Passioncoder\SimpleCurl\Facade',
),
```

## Usage

**Synopsis:**

```php
$response = Curl::get($url, [array $params, [array $options]]);
$response = Curl::post($url, [array $params, [array $options]]);

$url      A valid url
$params   The get/post parameters as key/value pair
$options  cURL options as defined here: http://www.php.net/manual/function.curl-setopt.php

```

**Example:**

```php
try {

	$response = Curl::get('http://example.com/data.json', ['foo' => 'bar'], [CURLOPT_HEADER => false]);

} catch (Passioncoder\SimpleCurl\Exception $e) {
	
	// curl error 
}

if ($response->header->http_code == 200) {
	
	var_dump($response->body);
}
```

## License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

