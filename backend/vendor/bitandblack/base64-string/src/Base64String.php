<?php

/**
 * Base64 String – Encodes files to base64 strings and decodes base64 strings to files.
 *
 * @author Tobias Köngeter
 * @copyright Copyright © Bit&Black
 * @link https://www.bitandblack.com
 * @license MIT
 */

namespace BitAndBlack\Base64String;

use BitAndBlack\Base64String\Exception\FileNotFoundException;
use Symfony\Component\Mime\MimeTypes;

/**
 * The Base64String class converts a file to a base64 encoded string.
 *
 * @package Web2PrintTest\Helper
 */
class Base64String
{
    /**
     * @var string
     */
    private $base64String;
    
    /**
     * @var string
     */
    private $mimeType;

    /**
     * Base64String constructor.
     *
     * @param string $file
     * @throws \BitAndBlack\Base64String\Exception\FileNotFoundException
     */
    public function __construct(string $file)
    {
        if (!file_exists($file)) {
            throw new FileNotFoundException($file);
        }
        
        $mimeTypes = new MimeTypes();
        $this->mimeType = $mimeTypes->guessMimeType($file) ?? 'application/octet-stream';
        
        $this->base64String = 'data:'.$this->mimeType.';'.
            'base64,'.base64_encode(
                (string) file_get_contents($file)
            )
        ;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getBase64String();
    }

    /**
     * @return string
     */
    public function getBase64String(): string
    {
        return $this->base64String;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }
}