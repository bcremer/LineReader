# LineFileReader

## Install

Via Composer

``` bash
$ composer require bcremer/line-file-reader
```

## Usage

``` php
$reader = new Bcremer\LineFileReader();
foreach ($reader->readLines('some/file.txt') as $line) {
    echo $line;
}
```

## Testing

``` bash
$ composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

