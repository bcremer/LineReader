# LineFileReader

[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]

LineFileReader is a library to read large files line by line in a memory efficient (constant) way.

## Install

Via Composer

```bash
$ composer require bcremer/line-file-reader
```

## Usage

```php
$reader = new \Bcremer\LineFileReader\LineFileReader();
foreach ($reader->readLines('some/file.txt') as $line) {
    echo $line;
}
```

## Testing

```bash
$ composer test
```

```bash
$ TEST_MAX_LINES=200000 composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/bcremer/LineFileReader/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/bcremer/LineFileReader.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/bcremer/LineFileReader.svg?style=flat-square

[link-travis]: https://travis-ci.org/bcremer/LineFileReader
[link-scrutinizer]: https://scrutinizer-ci.com/g/bcremer/LineFileReader/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/bcremer/LineFileReader
