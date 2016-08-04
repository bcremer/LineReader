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

Given we have a textfile (`some/file.txt`) with lines like:

```
Line 1
Line 2
Line 3
Line 4
Line 5
Line 6
Line 7
Line 8
Line 9
Line 10
```

### Read forwards

```php
$reader = new \Bcremer\LineFileReader\LineFileReader();
foreach ($reader->readLines('some/file.txt') as $line) {
    echo $line . "\n"
}
```

The output will be:

```
Line 1
Line 2
Line 3
Line 4
Line 5
...
```

To set an offset or a limit use the `\LimitIterator`:

```php
$reader = new \Bcremer\LineFileReader\LineFileReader();
$lineGenerator = $reader->readLines('some/file.txt');
$lineGenerator = new \LimitIterator($lineGenerator, 2, 5);
foreach ($lineGenerator as $line) {
    echo $line . "\n"
}
```

Will output line 3 to 7

```
Line 3
Line 4
Line 5
Line 6
Line 7
```

### Read backwards

```php
$reader = new \Bcremer\LineFileReader\LineFileReader();
foreach ($reader->readLinesBackwards('some/file.txt') as $line) {
    echo $line;
}
```

```
Line 10
Line 9
Line 8
Line 7
Line 6
...
```

Example: Read the last 5 lines in forward order:

```php
$reader = new \Bcremer\LineFileReader\LineFileReader();
$lineGenerator = $this->reader->readLinesBackwards('some/file.txt');
$lineGenerator = new \LimitIterator($lineGenerator, 0, 5);

$lines = array_reverse(iterator_to_array($lineGenerator));
foreach ($line as $line) {
    echo $line;
}
```

```
Line 6
Line 7
Line 8
Line 9
Line 10
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
