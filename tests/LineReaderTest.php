<?php
namespace Bcremer\LineReaderTests;

use Bcremer\LineReader\LineReader;

class LineReaderTest extends \PHPUnit_Framework_TestCase
{
    private static $maxLines;
    private static $testFile;

    static public function setUpBeforeClass()
    {
        self::$maxLines = (int)getenv('TEST_MAX_LINES') ?: 10000;
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

    /**
     * @requires PHP 7.0
     */
    public function testCanNotBeInstantiated()
    {
        $this->expectException(\Error::class);
        new LineReader();
    }

    public function testReadLinesThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot open file for reading: /tmp/invalid-file.txt');

        LineReader::readLines('/tmp/invalid-file.txt');
    }

    public function testReadLinesBackwardsThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot open file for reading: /tmp/invalid-file.txt');

        LineReader::readLinesBackwards('/tmp/invalid-file.txt');
    }

    public function testReadsAllLines()
    {
        $result = LineReader::readLines(self::$testFile);

        self::assertInstanceOf(\Traversable::class, $result);

        $firstLine = 1;
        $lastLine = self::$maxLines;
        $lineCount = self::$maxLines;
        $this->assertLines($result, $firstLine, $lastLine, $lineCount);
    }

    public function testReadsLinesByStartline()
    {
        $lineGenerator = LineReader::readLines(self::$testFile);
        $lineGenerator = new \LimitIterator($lineGenerator, 50);

        $firstLine = 51;
        $lastLine = self::$maxLines;
        $lineCount = self::$maxLines-50;
        $this->assertLines($lineGenerator, $firstLine, $lastLine, $lineCount);
    }

    public function testReadsLinesByLimit()
    {
        $lineGenerator = LineReader::readLines(self::$testFile);
        $lineGenerator = new \LimitIterator($lineGenerator, 50, 100);

        $firstLine = 51;
        $lastLine = 150;
        $lineCount = 100;
        $this->assertLines($lineGenerator, $firstLine, $lastLine, $lineCount);
    }

    public function testReadsLinesBackwards()
    {
        $lineGenerator = LineReader::readLinesBackwards(self::$testFile);

        $firstLine = self::$maxLines;
        $lastLine = 1;
        $lineCount = self::$maxLines;
        $this->assertLines($lineGenerator, $firstLine, $lastLine, $lineCount);
    }

    public function testReadsLinesBackwardsWithOffsetAndLimit()
    {
        $lineGenerator = LineReader::readLinesBackwards(self::$testFile);
        $lineGenerator = new \LimitIterator($lineGenerator, 10, 50);

        $firstLine = self::$maxLines-10;
        $lastLine = self::$maxLines-59;
        $lineCount = 50;
        $this->assertLines($lineGenerator, $firstLine, $lastLine, $lineCount);
    }

    public function testEmptyFile()
    {
        $testFile = __DIR__.'/testfile_empty.txt';
        $content = '';
        file_put_contents($testFile, $content);

        $lineGenerator = LineReader::readLines($testFile);
        self::assertSame([], iterator_to_array($lineGenerator));

        $lineGenerator = LineReader::readLinesBackwards($testFile);
        self::assertSame([], iterator_to_array($lineGenerator));
    }

    public function testFileWithLeadingAndTrailingNewlines()
    {
        $testFile = __DIR__.'/testfile_space.txt';

        $content = <<<CONTENT


Line 1


Line 4
Line 5


CONTENT;

        file_put_contents($testFile, $content);

        self::assertSame(
            [
                '',
                '',
                'Line 1',
                '',
                '',
                'Line 4',
                'Line 5',
                '',
            ],
            iterator_to_array(LineReader::readLines($testFile))
        );

        self::assertSame(
            [
                '',
                'Line 5',
                'Line 4',
                '',
                '',
                'Line 1',
                '',
                '',
            ],
            iterator_to_array(LineReader::readLinesBackwards($testFile))
        );
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
