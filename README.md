# Mars rover by á

An (optionally interactive) mars rover that runs in the command line and has a text-based user interface. 

## Requirements

* PHP 8+
* Composer or Docker

## Install

### Using Docker
* Clone this repository
* `cd mars-rover`
* `docker/up`
* `docker/composer`
* Launch: `docker app`

### Using composer
When using composer and php 7 
* Clone this repository
* `cd mars-rover`
* `composer install`
* Launch: `php rover.php`

## Usage

When in the interactive mode, use `f` to move forward, `r` to rotate right, and `l` to rotate left.

When in non-interactive mode just 

## Testing
Run `docker/test` or, alternatively, `phpunit`. 

Tests cover basic non-interactive mode and CLImate input. Those are integration tests on top of Mockery, and some are a bit silly and with the sole purpose of playing with more assertions. Unit testing would have been nice too. 

## Thanks!

Heavily used third party code:

* [PHP-Snake](https://github.com/akondas/php-snake) by Arkadiusz Kondas under a MIT License. 
* [CLIMate](https://climate.thephpleague.com/) by The PHP League of Extraordinary Packages under a MIT License, to make CLI input way cooler. 
* [Mockery](https://github.com/mockery/mockery) by Pádraic Brady and others under a New BSD License, to partially mock classes and allow integration tests.

## License

GPL2.

## Author

Álvaro Martínez i Majado (a@alvaro.cat)
