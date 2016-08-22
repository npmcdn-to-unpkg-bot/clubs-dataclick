<?php
namespace App\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;

class ApiException extends \Exception
{
    /**
     * @var \Illuminate\Support\MessageBag
     */
    public $errors;

    public function __construct(MessageBag $errors, $message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->errors = $errors;
    }
}