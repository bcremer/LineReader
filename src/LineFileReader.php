<?php

namespace Bcremer\LineFileReader;

class LineFileReader
{
    /**
     * @param string $filePath
     * @param int $startLine
     * @param int|null $maxLines
     * @return \Traversable
     * @throws \InvalidArgumentException if $filePath is not readable
     */
    public function readLines($filePath, $startLine = 0, $maxLines = null)
    {
        if (!$fh = @fopen($filePath, 'r')) {
            throw new \InvalidArgumentException('Cannot open file for reading: ' . $filePath);
        }

        $iter = $this->read($startLine, $fh);

        if ($maxLines) {
            return new \LimitIterator($iter, null, $maxLines);
        } else {
            return $iter;
        }
    }

    /**
     * @param int $startLine
     * @param resource $fh
     * @return \Generator
     */
    private function read($startLine, $fh)
    {
        $lineCounter = 0;
        while (false !== $line = fgets($fh)) {
            $lineCounter++;
            if ($lineCounter < $startLine) {
                continue;
            }

            yield $line;
        }

        fclose($fh);
    }
}
