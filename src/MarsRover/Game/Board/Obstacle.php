<?php

declare(strict_types=1);

namespace MarsRover\Game\Board;

class Obstacle extends Point
{
    /**
     * @var string
     */
    private $char = "!";

    /**
     * @param int $row
     * @param int $col
     */
    public function __construct(int $row, int $col)
    {
        parent::__construct($row, $col, $this->char);
    }
}
