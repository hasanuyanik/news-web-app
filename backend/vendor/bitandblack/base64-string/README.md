[![PHP from Packagist](https://img.shields.io/packagist/php-v/bitandblack/base64-string)](http://www.php.net)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/9424a974c1b6464e8dc40bf5dbe5e7a3)](https://www.codacy.com/bb/wirbelwild/base64-string/dashboard?utm_source=tobiaskoengeter@bitbucket.org&amp;utm_medium=referral&amp;utm_content=wirbelwild/base64-string&amp;utm_campaign=Badge_Grade)
[![Latest Stable Version](https://poser.pugx.org/bitandblack/base64-string/v/stable)](https://packagist.org/packages/bitandblack/base64-string)
[![Total Downloads](https://poser.pugx.org/bitandblack/base64-string/downloads)](https://packagist.org/packages/bitandblack/base64-string)
[![License](https://poser.pugx.org/bitandblack/base64-string/license)](https://packagist.org/packages/bitandblack/base64-string)

# Bit&Black Base64 String

Encodes files to base64 strings and decodes base64 strings to files.

## Installation

This library is made for the use with [Composer](https://packagist.org/packages/bitandblack/base64-string). Add it to your project by running `$ composer require bitandblack/base64-string`.

## Usage

Initialize the `Base64String` with the path to the file you want to encode:

````php
<?php

use BitAndBlack\Base64String\Base64String;

$base64String = new Base64String('/path/to/myfile.txt');
````

You can access the single parts of the base64 string now like that:

````php
<?php

/** Will echo something like "data:text/plain;base64,SGVsbG8gV29ybGQh..." */
echo $base64String->getBase64String();

/** Will echo "text/plain" */
echo $base64String->getMimeType();
````

Or simply echo the object:

````php
<?php

/** This will also echo something like "data:text/plain;base64,SGVsbG8gV29ybGQh..." */
echo new Base64String('/path/to/myfile.txt');
````

### Backwards

You can also convert a base64 encoded string and extract its parts:

````php
<?php

use BitAndBlack\Base64String\Base64File;

$base64File = new Base64File('data:text/plain;base64,SGVsbG8gV29ybGQh');
````

You can access the single parts of the file now like that:

````php
<?php

/** Will echo "txt" */
echo $base64File->getExtension();

/** Will echo "Hello World!" using the input string above */
echo $base64File->getContent();

/** Will echo "text/plain" */
echo $base64File->getMimeType();
````

With that information it is easy to write that file to the file system:

````php
<?php

file_put_contents(
    'myfile.txt', /** Or `'myfile.'.$base64File->getExtension(),` if you prefer. */
    $base64File
);
````

## Help

If you have any questions, feel free to contact us under `hello@bitandblack.com`.

Further information about Bit&Black can be found under [www.bitandblack.com](https://www.bitandblack.com).