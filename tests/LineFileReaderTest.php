<?php
namespace Bcremer\LineFileReaderTests;

use Bcremer\LineFileReader\LineFileReader;

class LineFileReaderTest extends \PHPUnit_Framework_TestCase
{
    private static $maxLines;
    private static $testFile;

    /**
     * @var LineFileReader
     */
    private $reader;

    static public function setUpBeforeClass()
    {
        self::$maxLines = (int)getenv('TEST_MAX_LINES') ?: 1000;
        self::$testFile = __DIR__.'/testfile_'.self::$maxLines.'.txt';

        if (is_file(self::$testFile)) {
            return;
        }

        $fh = fopen(self::$testFile, 'w');
        for ($i = 1; $i <= self::$maxLines; $i++) {
            fwrite($fh, "Line $i\n");
        }
        fclose($fh);
    }

    protected function setUp()
    {
        $this->reader = new LineFileReader();
    }

    public function testThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $result = $this->reader->readLines('/tmp/invalid-file.txt');
        iterator_to_array($result);
    }

    public function testReadsAllLines()
    {
        $result = $this->reader->readLines(self::$testFile);

        self::assertInstanceOf(\Traversable::class, $result);

        $firstLine = 1;
        $lastLine = self::$maxLines;
        $lineCount = self::$maxLines;
        $this->assertLines($result, $firstLine, $lastLine, $lineCount);
    }

    public function testReadsLinesByStartline()
    {
        $lineEmiter = $this->reader->readLines(self::$testFile);
        $lineEmiter = new \LimitIterator($lineEmiter, 50);

        $firstLine = 51;
        $lastLine = self::$maxLines;
        $lineCount = self::$maxLines-50;
        $this->assertLines($lineEmiter, $firstLine, $lastLine, $lineCount);
    }

    public function testReadsLinesByLimit()
    {
        $lineEmiter = $this->reader->readLines(self::$testFile);
        $lineEmiter = new \LimitIterator($lineEmiter, 50, 100);

        $firstLine = 51;
        $lastLine = 150;
        $lineCount = 100;
        $this->assertLines($lineEmiter, $firstLine, $lastLine, $lineCount);
    }

    /**
     * Runs the generator and asserts on first, last and the total line count
     *
     * @param \Traversable $generator
     * @param int $firstLine
     * @param int $lastLine
     * @param int $lineCount
     */
    private function assertLines(\Traversable $generator, $firstLine, $lastLine, $lineCount)
    {
        $count = 0;
        $line = '';
        foreach ($generator as $line) {
            if ($count === 0) {
                self::assertSame("Line $firstLine", $line, 'Expect first line');
            }
            $count++;
        }

        self::assertSame("Line $lastLine", $line, 'Expect last line');
        self::assertSame($lineCount, $count, 'Expect total line count');
    }
}
