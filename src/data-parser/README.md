# data parser

simple data parser for php

driver list:

- json(by `json_encode`)
- php(by `serialize`)
- swoole(by extension `swoole` OR `swoole_serialize`)
- msgpack(by extension `msgpack`)

## Install

- composer command

```bash
composer require swoft/data-parser
```

## Usage

```php
$parser = new SwooleParser();
// $parser = new JsonParser();
// $parser = new PhpParser();
// $parser = new MsgPackParser();

// encode
$encoded = $parser->encode($data);

// decode
$decoded = $parser->encode($encoded);
```

## Resources

* [Documentation](https://doc.swoft.org)
* [Contributing](https://github.com/swoft-cloud/swoft/blob/master/CONTRIBUTING.md)
* [Report Issues][issues] and [Send Pull Requests][pulls] in the [Main Swoft Repository][repository]

[pulls]: https://github.com/swoft-cloud/swoft-component/pulls
[repository]: https://github.com/swoft-cloud/swoft
[issues]: https://github.com/swoft-cloud/swoft/issues

## Unit testing

```bash
phpunit 
```

## LICENSE

The Component is open-sourced software licensed under the [Apache license](LICENSE).

