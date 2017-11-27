# DTRT WP Weather

Displays historical weather information for the GPS location determined by the Featured Image.

## Setup

Please read [DTRT WP Plugin: Set up a plugin](https://github.com/dotherightthing/wpdtrt-plugin#set-up-a-plugin).

## Usage

Please read the [WordPress readme.txt](readme.txt) for usage instructions.

## Tests

### Install and activate this plugin

See *Setup*, above.

Test that PHPUnit was installed successfully:

```
$ wp --info
PHP binary:	/usr/bin/php
PHP version:	5.6.30
php.ini used:	
WP-CLI root dir:	phar://wp-cli.phar
WP-CLI packages dir:	
WP-CLI global config:	
WP-CLI project config:	
WP-CLI version:	1.1.0
```

### Install PHPUnit

(PHPUnit is included in the Composer download, but [that doesn't install it globally](https://github.com/dotherightthing/generator-wp-plugin-boilerplate/issues/26)).

```
$ wget https://phar.phpunit.de/phpunit-5.7.phar
$ chmod +x phpunit-5.7.phar
$ sudo mv phpunit-5.7.phar /usr/local/bin/phpunit
```

Test that PHPUnit was installed successfully:

```
$ phpunit --version
PHPUnit 5.7.25 by Sebastian Bergmann and contributors.
```

### Generate WordPress test environment

Note: Tests are _destructive_, so this requires a _dedicated_ testing database.

```
$ cd wp-content/plugins/wpdtrt-weather
$ bash bin/install-wp-tests.sh TEST_DB_NAME TEST_DB_USER TEST_DB_PASSWORD 127.0.0.1 latest
```

### Run tests in Terminal

```
$ phpunit
```
