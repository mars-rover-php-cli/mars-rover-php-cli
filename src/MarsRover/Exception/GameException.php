<?php

declare(strict_types=1);

namespace MarsRover\Exception;

class GameException extends \Exception
{
    /**
     * @return GameException
     */
    public static function marsRoverCollision()
    {
        return new self('MarsRover collision');
    }
}
