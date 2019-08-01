# SwagAdvDevBundle
## Description

This plugin is used as example in the Shopware Developer Advanced Training.
It provides basic product bundle functionality.

## Unit tests
To execute the PhpUnit tests of this plugins, use the following commands.

```bash
composer install
```
Installs the plugin dev tools

```bash
./psh local:init
```
Sets up a local test database

```bash
./psh local:unit
```
Executes the unit tests

There are also commands to setup and execute the tests in a Docker container.
Just execute `./psh` for a list of all available commands.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
