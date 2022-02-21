<?php

declare(strict_types=1);

namespace MarsRover\Game;

interface Direction
{
    const NORTH = 'north';
    const SOUTH = 'south';
    const WEST = 'west';
    const EAST = 'east';
    const LEFT = [
        self::NORTH => self::WEST,
        self::SOUTH => self::EAST,
        self::WEST => self::SOUTH,
        self::EAST => self::NORTH,
    ];

    const RIGHT = [
        self::NORTH => self::EAST,
        self::SOUTH => self::WEST,
        self::WEST => self::NORTH,
        self::EAST => self::SOUTH,
    ];
}
