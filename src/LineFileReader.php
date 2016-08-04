<?php

namespace Bcremer\LineFileReader;

class LineFileReader
{
    /**
     * @param string $filePath
     * @return \Generator
     * @throws \InvalidArgumentException if $filePath is not readable
     */
    public function readLines($filePath)
    {
        if (!$fh = @fopen($filePath, 'r')) {
            throw new \InvalidArgumentException('Cannot open file for reading: ' . $filePath);
        }

        return $this->read($fh);
    }

    /**
     * @param resource $fh
     * @return \Generator
     */
    private function read($fh)
    {
        while (false !== $line = fgets($fh)) {
            yield rtrim($line, "\n");
        }

        fclose($fh);
    }

}

