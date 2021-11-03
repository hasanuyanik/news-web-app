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

use BitAndBlack\Base64String\Base64File;
use BitAndBlack\Base64String\Exception\InvalidInputStringException;
use Generator;
use PHPUnit\Framework\TestCase;

/**
 * Class Base64FileTest. 
 * 
 * @package BitAndBlack\Base64String\Tests
 */
class Base64FileTest extends TestCase
{
    public function testCanCreateFile(): void 
    {
        $base64File = new Base64File('data:text/plain;base64,SGVsbG8gV29ybGQh');
        
        self::assertSame(
            'text/plain',
            $base64File->getMimeType()
        );

        self::assertSame(
            'Hello World!',
            $base64File->getContent()
        );    
        
        self::assertSame(
            'txt',
            $base64File->getExtension()
        );
    }

    /**
     * @dataProvider getInvalidInputString
     * @param string $input
     * @throws \BitAndBlack\Base64String\Exception\InvalidInputStringException
     */
    public function testThrowsException(string $input): void 
    {
        $this->expectException(InvalidInputStringException::class);
        new Base64File($input);
    }

    /**
     * @return \Generator<array<int, string>>
     */
    public function getInvalidInputString(): Generator
    {
        yield ['data:text/plain,base64,SGVsbG8gV29ybGQh'];
        yield ['text/plain;base64,SGVsbG8gV29ybGQh'];
        yield ['data:text/plain;SGVsbG8gV29ybGQh'];
    }
}
