<?php

declare(strict_types=1);

namespace MarsRover\Exception;

class InvalidInputException extends \Exception
{
    /**
     * @return InvalidInputException
     */
    public static function wrongInput()
    {
        return new self('Wrong input');
    }
}
