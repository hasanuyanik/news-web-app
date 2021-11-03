<?php

/**
 * Base64 String – Encodes files to base64 strings and decodes base64 strings to files.
 *
 * @author Tobias Köngeter
 * @copyright Copyright © Bit&Black
 * @link https://www.bitandblack.com
 * @license MIT
 */

namespace BitAndBlack\Base64String\Tests;

use BitAndBlack\Base64String\Base64String;
use BitAndBlack\Base64String\Exception\FileNotFoundException;
use Generator;
use PHPUnit\Framework\TestCase;

/**
 * Class Base64StringTest.
 * 
 * @package BitAndBlack\Base64String\Tests
 */
class Base64StringTest extends TestCase
{
    /**
     * @throws \BitAndBlack\Base64String\Exception\FileNotFoundException
     */
    public function testCanCreateString(): void 
    {
        $base64String = new Base64String(__DIR__.DIRECTORY_SEPARATOR.'test-file.txt');

        self::assertSame(
            'text/plain',
            $base64String->getMimeType()
        );
        
        self::assertSame(
            'data:text/plain;base64,SGVsbG8gV29ybGQh',
            $base64String->getBase64String()
        );
    }

    /**
     * @dataProvider getMissingFile
     * @param string $file
     * @throws \BitAndBlack\Base64String\Exception\FileNotFoundException
     */
    public function testThrowsException(string $file): void 
    {
        $this->expectException(FileNotFoundException::class);
        new Base64String($file);
    }

    /**
     * @return \Generator<array<int, string>>
     */
    public function getMissingFile(): Generator
    {
        yield ['file-missing.txt'];
    }
}
