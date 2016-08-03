<?php
namespace Bcremer\LineFileReaderTests;

use Bcremer\LineFileReader\LineFileReader;

class LineFileReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LineFileReader
     */
    private $reader;

    const MAX_LINES = 100000;
    const TEST_FILE = __DIR__.'/testfile_'.self::MAX_LINES.'.txt';

    static public function setUpBeforeClass()
    {
        if (is_file(self::TEST_FILE)) {
            return;
        }

        $fh = fopen(self::TEST_FILE, 'w');
        for ($i = 1; $i <= self::MAX_LINES; $i++) {
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
        $result = $this->reader->readLines(self::TEST_FILE);

        self::assertInstanceOf(\Traversable::class, $result);

        $firstLine = 1;
        $lastLine = self::MAX_LINES;
        $lineCount = self::MAX_LINES;
        $this->assertLines($result, $firstLine, $lastLine, $lineCount);
    }

    public function testReadsLinesByStartline()
    {
        $result = $this->reader->readLines(self::TEST_FILE, 50);

        $firstLine = 50;
        $lastLine = self::MAX_LINES;
        $lineCount = self::MAX_LINES-49;
        $this->assertLines($result, $firstLine, $lastLine, $lineCount);
    }

    public function testReadsLinesByLimit()
    {
        $result = $this->reader->readLines(self::TEST_FILE, 50, 100);

        $firstLine = 50;
        $lastLine = 149;
        $lineCount = 100;
        $this->assertLines($result, $firstLine, $lastLine, $lineCount);
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
                self::assertSame("Line $firstLine\n", $line);
            }
            $count++;
        }

        self::assertSame("Line $lastLine\n", $line);
        self::assertSame($lineCount, $count);
    }
}
