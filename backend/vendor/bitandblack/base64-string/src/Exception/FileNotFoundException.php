<?php

/**
 * Base64 String – Encodes files to base64 strings and decodes base64 strings to files.
 *
 * @author Tobias Köngeter
 * @copyright Copyright © Bit&Black
 * @link https://www.bitandblack.com
 * @license MIT
 */

namespace BitAndBlack\Base64String\Exception;

use BitAndBlack\Base64String\Exception;
use Throwable;

/**
 * Class FileNotFoundException.
 * 
 * @package BitAndBlack\Base64String\Exception
 */
class FileNotFoundException extends Exception
{
    /**
     * FileNotFoundException constructor.
     * 
     * @param string $file
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $file, int $code = 0, Throwable $previous = null)
    {
        parent::__construct('File "'.$file.'" is not existing.', $code, $previous);
    }
}