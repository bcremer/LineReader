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

### Read forwards

```php
$reader = new \Bcremer\LineFileReader\LineFileReader();
foreach ($reader->readLines('some/file.txt') as $line) {
    echo $line;
}
```

To set an offset or a limit use the `\LimitIterator`:

```php
$reader = new \Bcremer\LineFileReader\LineFileReader();
$lineGenerator = $reader->readLines('some/file.txt');
$lineGenerator = new \LimitIterator($lineGenerator, 50, 100);

// Will output line 51 to 150
foreach ($lineGenerator as $line) {
    echo $line;
}
```

### Read backwards


```php
$reader = new \Bcremer\LineFileReader\LineFileReader();
foreach ($reader->readLinesBackwards('some/file.txt') as $line) {
    echo $line;
}
```

Example: Read the last 10 line in forward order:

```php
$reader = new \Bcremer\LineFileReader\LineFileReader();
$lineGenerator = $this->reader->readLinesBackwards(self::$testFile);
$lineGenerator = new \LimitIterator($lineGenerator, 0, 10);

$lines = array_reverse(iterator_to_array($lineGenerator));
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
