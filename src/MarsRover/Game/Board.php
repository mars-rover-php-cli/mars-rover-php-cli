<?php

declare(strict_types=1);

namespace MarsRover\Game;

use MarsRover\Exception\GameException;
use MarsRover\Game\Board\Obstacle;
use MarsRover\Game\Board\Point;
use MarsRover\Terminal;
use MarsRover\Terminal\Char;

class Board
{
    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @var array
     */
    private $map;

    /**
     * @var array
     */
    private $sourceMap;

    /**
     * @var Vehicle
     */
    private $marsrover;

    /**
     * @var Obstacle[]:array
     */
    private $obstacles;

    /**
     * @param int $width
     * @param int $height
     */
    public function __construct(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;

        $this->marsrover = new Vehicle($height, $width);
        $this->randomObstacles(50);

        $this->generateMap();
        $this->generateOutline();
        $this->sourceMap = $this->map;

        $this->applyElements();
    }

    public function randomObstacles(int $count)
    {
        for ($i = 0; $i < $count; ++$i) {
            $col = rand(1, $this->width - 2);
            $row = rand(1, $this->height - 2);

            $this->obstacles[] = new Obstacle($row, $col);
        }
    }

    public function moveMarsRover(string $input)
    {
        $this->marsrover->move($input);
        $this->checkObstacles();
        $this->applyElements();
    }

    private function checkObstacles()
    {
        $head = $this->marsrover->getPoints()[0];

        if (!empty($this->obstacles)) {
            foreach ($this->obstacles as $index => $obstacle) {
                if ($head->overlaps($obstacle)) {
                    throw GameException::marsRoverCollision();
                    // $this->marsrover->advance();
                    // unset($this->obstacles[$index]);
                    // $this->randomObstacles(1);
                }
            }
        }
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return array
     */
    public function getMap()
    {
        return $this->map;
    }

    public function writeGameOver(string $sequence)
    {
        $text = 'Collision after ' . $sequence;
        $length = strlen($text);
        $col = ($this->width / 2) - ($length / 2);
        $row = $this->height / 2;

        for ($i = 0; $i < $length; ++$i) {
            $this->map[$row][$col] = $text[$i];
            ++$col;
        }
    }

    private function applyElements()
    {
        $this->map = $this->sourceMap;

        foreach ($this->marsrover->getPoints() as $point) {
            $this->applyPoint($point);
        }

        if (!empty($this->obstacles)) {
            foreach ($this->obstacles as $obstacle) {
                $this->applyPoint($obstacle);
            }
        }
    }

    /**
     * @param Point $point
     */
    private function applyPoint(Point $point)
    {
        $this->map[$point->getRow()][$point->getCol()] = $point->getChar();
    }

    private function generateMap()
    {
        for ($i = 0; $i < $this->height; ++$i) {
            $this->map[$i] = array_fill(0, $this->width, ' ');
        }
    }

    private function generateOutline()
    {
        $this->map[0][0] = Char::boxTopLeft();
        $this->map[0][$this->width - 1] = Char::boxTopRight();

        $this->generateHLine(0, 1, $this->width - 2, Char::boxHorizontal());
        $this->generateHLine($this->height - 1, 1, $this->width - 2, Char::boxHorizontal());

        $this->generateVLine(0, 1, $this->height - 2, Char::boxVertical());
        $this->generateVLine($this->width - 1, 1, $this->height - 2, Char::boxVertical());

        $this->map[$this->height - 1][0] = Char::boxBottomLeft();
        $this->map[$this->height - 1][$this->width - 1] = Char::boxBottomRight();
    }

    /**
     * @param int    $row
     * @param int    $start
     * @param int    $cols
     * @param string $char
     */
    private function generateHLine(int $row, int $start, int $cols, string $char)
    {
        for ($i = 0; $i < $cols; ++$i) {
            $this->map[$row][$start + $i] = $char;
        }
    }

    /**
     * @param int    $col
     * @param int    $start
     * @param int    $rows
     * @param string $char
     */
    private function generateVLine(int $col, int $start, int $rows, string $char)
    {
        for ($i = 0; $i < $rows; ++$i) {
            $this->map[$start + $i][$col] = $char;
        }
    }
}
