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

use BitAndBlack\Base64String\Exception\InvalidInputStringException;
use Symfony\Component\Mime\MimeTypes;

/**
 * The Base64File class converts a base64 encoded string to a file.
 * 
 * @package BitAndBlack\Base64String
 */
class Base64File
{
    /**
     * @var string
     */
    private $extension;
    
    /**
     * @var string
     */
    private $content;
    
    /**
     * @var string
     */
    private $mimeType;

    /**
     * Base64File constructor.
     *
     * @param string $input
     * @throws \BitAndBlack\Base64String\Exception\InvalidInputStringException
     */
    public function __construct(string $input)
    {
        $inputParts = explode(';', $input);

        $mimeType = array_shift($inputParts);
        $mimeType = substr($mimeType, strlen('data:'));
        
        if (!is_string($mimeType)) {
            throw new InvalidInputStringException('Cannot extract mime type from input string.');
        }
        
        $this->mimeType = $mimeType;
        
        $mimeTypes = new MimeTypes();
        $extensions = $mimeTypes->getExtensions($this->mimeType);

        if ([] === $extensions) {
            throw new InvalidInputStringException('Cannot extract extension from mime type.');
        }
        
        $this->extension = (string) $extensions[0];
        
        $content = implode($inputParts);
        
        if (!str_starts_with($content, 'base64,')) {
            throw new InvalidInputStringException('Cannot extract encoded parts of input string.');
        }
        
        $content = substr($content, strlen('base64,'));

        if (!is_string($content)) {
            throw new InvalidInputStringException('Cannot extract encoded parts of input string.');
        }
        
        $this->content = base64_decode($content);
    }

    /**
     * @return string
     */
    public function __toString(): string 
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }
}