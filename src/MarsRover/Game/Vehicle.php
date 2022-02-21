<?php

declare(strict_types=1);

namespace MarsRover\Game;

use MarsRover\Exception\InvalidInputException;
use MarsRover\Game\Board\Point;
use MarsRover\Terminal;
use MarsRover\Terminal\Char;

class Vehicle
{
    /**
     * @var Point[]|array
     */
    private $points;

    /**
     * @var string
     */
    private $direction = Direction::EAST;

    /**
     * @var int
     */
    private $boardRows;

    /**
     * @var int
     */
    private $boardCols;

    /**
     * @var Point[]|null
     */
    private $lastPoint;

    /**
     * @var array
     */
    private $userInstructions = [];

    /**
     * @param int $boardRows
     * @param int $boardCols
     */
    public function __construct(int $boardRows, int $boardCols)
    {
        if (Terminal::$interactive === false) {
            $head = new Point(intval(Terminal::$nonInteractiveInput['starting']['x']), intval(Terminal::$nonInteractiveInput['starting']['y']), Char::block());
        } else {
            $head = new Point(intval($boardRows / 2), intval($boardCols / 2), Char::block());
        }
        $this->boardCols = $boardCols;
        $this->boardRows = $boardRows;

        // rover is a single point
        $this->points[] = $head;


        $this->userInstructions = Terminal::inputHandlerForSequenceMode();
    }

    public function move(string $input)
    {
        $this->changeDirection($input);


        $row = $this->points[0]->getRow();
        $col = $this->points[0]->getCol();

        switch ($this->direction) {
            case Direction::EAST:
                $col++;
                break;
            case Direction::WEST:
                $col--;
                break;
            case Direction::NORTH:
                $row--;
                break;
            case Direction::SOUTH:
                $row++;
                break;
        }

        if ($col >= $this->boardCols - 1) {
            $col = 1;
        } elseif ($col < 1) {
            $col = $this->boardCols - 2;
        }

        if ($row >= $this->boardRows - 1) {
            $row = 1;
        } elseif ($row < 1) {
            $row = $this->boardRows - 2;
        }

        //the rover does not need shades
        //$this->points[0]->setChar(Char::shadeBlock());
        $next = new Point($row, $col, Char::block());

        array_unshift($this->points, $next);
        $this->lastPoint = array_pop($this->points);
    }

    public function advance()
    {
        array_push($this->points, $this->lastPoint);
    }

    /**
     * @param string $input
     */
    private function changeDirection(string $input)
    {
        if ('l' === $input) {
            $this->direction = Direction::LEFT[$this->direction];
        } elseif ('r' === $input) {
            $this->direction = Direction::RIGHT[$this->direction];
        } elseif ('f' !== $input) {
            throw new InvalidInputException('Invalid input');
        }
    }

    /**
     * @return array|Point[]
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     */
    public function setDirection(string $direction)
    {

        if (Terminal::$interactive !== true) {
            $this->direction = self::$userInstructions['direction'];
        } else {
            $this->direction = $direction;
        }
    }
}
