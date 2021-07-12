# thinq-php

[![Latest Stable Version](https://poser.pugx.org/dutchie027/thinq/v)](//packagist.org/packages/dutchie027/thinq)
[![Total Downloads](https://poser.pugx.org/dutchie027/Thinq/downloads)](//packagist.org/packages/dutchie027/thinq)
[![License](https://poser.pugx.org/dutchie027/thinq/license)](//packagist.org/packages/dutchie027/thinq)
[![CodeFactor](https://www.codefactor.io/repository/github/dutchie027/thinq-php/badge)](https://www.codefactor.io/repository/github/dutchie027/thinq-php)

PHP Library Intended to Interact with [Thinq's API](https://apidocs.thinq.com/)

## Installation

```php
composer require dutchie027/thinq
```

## Usage

```php
// require the composer library
require_once ('vendor/autoload.php');

//make the connction to the API for use
$api = new dutchie027\Thinq\API(THINQ_USER, THINQ_TOKEN);

...
```

## Requirements

The libarary assumes you have three defined variables:

```php
define ('THINQ_USER', 'myUserName');
define ('THINQ_TOKEN', '867530986753098675309');
define ('THINQ_ACCOUNT_ID', '12345678');
```

in order to work properly. `THINQ_USER` and `THINQ_TOKEN` are fed to the API as parameters for login and header information. `THINQ_ACCOUNT_ID` is used in making URLs on the back end. These constants can be defined in your script or they can be imported or included in a constant file (preferred).

## General Information

### Class Listing

The library has the following classes:

* [API](/docs/API.md)
* [CNAM](/docs/CNAM.md)
* [Inbound](/docs/Inbound.md)
* [LRN](/docs/LRN.md)
* [Outbound](/docs/Outbound.md)
* [Text](/docs/Text.md)

## Class Information

### API

The main connection requires at minimum, a username and token.

Once you have a user and token, you can simply connect with it or you can add options

```php
// Ensure we have the composer libraries
require_once ('vendor/autoload.php');

// Instantiate with defaults
$api = new dutchie027\Thinq\API(THINQ_USER, THINQ_TOKEN);

// Instantiate without defaults, this allows you to change things
// like log location, directory, the tag and possible future settings.
$settings = [
  'log_dir' => '/tmp',
  'log_name' => 'thinqi',
  'log_tag' => 'thinq-api',
  'log_level' => 'error'
];

$api = new dutchie027\Thinq\API(THINQ_USER, THINQ_TOKEN, $settings);
```

#### Settings

The default settings are fine, however you might want to override the defaults or use your own.**NOTE: All settings are optional and you don't need to provide any**.

| Field       | Type   | Description                                                                                                                                                                                 | Default Value                                                                          |
| ----------- | ------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------- |
| `log_dir`   | string | The directory where the log file is stored                                                                                                                                                  | [sys_get_temp_dir()](https://www.php.net/manual/en/function.sys-get-temp-dir.php)      |
| `log_name`  | string | The name of the log file that is created in `log_dir`. If you don't put .log at the end, it will append it                                                                                  | 6 random characters + [time()](https://www.php.net/manual/en/function.time.php) + .log |
| `log_tag`   | string | If you share this log file with other applications, this is the tag used in the log file                                                                                                    | `thinq`                                                                                |
| `log_level` | string | The level of logging the application will do. This must be either `debug`, `info`, `notice`, `warning`, `critical` or `error`. If it is not one of those values it will fail to the default | `warning`                                                                              |

## To-Do

* Bring in more of the function(s) from Thinq
* Document the class(es) with proper doc blocks better
* Clean up the code a bit more
* Stuff I'm obviously missing...

## Contributing

If you're having problems, spot a bug, or have a feature suggestion, [file an issue](https://github.com/dutchie027/thinq-php/issues). If you want, feel free to fork the package and make a pull request. This is a work in progresss as I get more info and further test the API.
