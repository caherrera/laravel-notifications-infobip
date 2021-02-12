<?php

namespace Caherrera\Laravel\Notifications\Channels\Infobip\Omni\Exceptions;

use Throwable;

class AuthMethodException extends BaseException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct("Api auth method $message does not exists", $code, $previous);
    }
}
