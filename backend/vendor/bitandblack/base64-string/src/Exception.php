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

use Throwable;

/**
 * Class Exception.
 * 
 * @package BitAndBlack\Base64String
 */
class Exception extends \Exception
{
    /**
     * Exception constructor.
     * 
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}