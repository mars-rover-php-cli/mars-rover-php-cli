<?php

declare(strict_types=1);

namespace MarsRover;

use MarsRover\Exception\GameException;
use MarsRover\Exception\InvalidInputException;
use MarsRover\Game\Board;
use MarsRover\Game\Drawer;
use MarsRover\Terminal\InputHandler;

class Game
{
    /**
     * @var Terminal
     */
    private $terminal;

    /**
     * @var Board
     */
    private $board;

    /**
     * @var Drawer
     */
    private $drawer;

    public function __construct()
    {

        $this->terminal = new Terminal();
        $this->board = new Board(32, 32);
        $this->drawer = new Drawer(STDOUT);

        $this->drawBoard();
    }

    public function run()
    {
        $actualSequence = [];
        try {
            while (true) {
                do {
                    if (Terminal::$interactive === true) {
                        $input = $this->terminal->getChar();
                        $isCorrectInput = true;
                    }
                    try {
                        if (Terminal::$interactive === false) {
                            $sequence = Terminal::$nonInteractiveInput['sequence'];

                            if (!is_array($sequence) || count($sequence) === 0) {
                                return;
                            }

                            $input = array_shift($sequence);

                            Terminal::$nonInteractiveInput['sequence'] = $sequence;

                            $isCorrectInput = true;
                        }
                        $this->board->moveMarsRover($input);
                        $actualSequence[] = $input;
                        if (Terminal::$interactive === false) {
                            usleep(500000);
                        }
                    } catch (InvalidInputException $e) {
                        $isCorrectInput = false;
                    }
                } while (!$isCorrectInput);
                $this->drawBoard();
                usleep(180000);
            }
        } catch (GameException $exception) {
            $this->gameOver(implode($actualSequence));
        }
    }

    public function gameOver(string $actualSequence)
    {
        $this->board->writeGameOver($actualSequence);
        $this->drawBoard();
    }

    private function drawBoard()
    {
        $this->drawer->draw($this->board);
    }
}
