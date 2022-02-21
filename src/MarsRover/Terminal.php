<?php

declare(strict_types=1);

namespace MarsRover;

use MarsRover\Game\Direction;

class Terminal
{
    /**
     * @var int
     */
    private $width;

    /**
     * @var
     */
    private $height;

    /**
     * @var
     */
    public static $interactive;

    /**
     * @var
     */
    public static $nonInteractiveInput;

    public function __construct()
    {

        self::$interactive = null;


        stream_set_blocking(STDIN, true);
        $this->inputHandlerForSequenceMode();
        stream_set_blocking(STDIN, false);


        // let's hardcode 200x200 because that's what the requirements stated
        $this->width = 20;
        $this->height = 0;
        stream_set_blocking(STDIN, false);
    }

    public static function inputHandlerForSequenceMode()
    {
        if (self::$interactive === null) {
            $cli = new \League\CLImate\CLImate;
            $interactive = $cli->input('Use interactive mode? (y/n)');
            $interactive->accept(['y', 'yes', 'n', 'no'], false);
            $interactive->defaultTo('y');

            if (strpos($interactive->prompt(), 'n') === 0) {
                self::$interactive = false;

                $roverStartingPointX = $cli->input('Rover strating point X coordinate (1 to 32):');
                $roverStartingPointX->accept(function ($x) {
                    return ($x >= 1 && $x <= 32);
                });
                $roverStartingPointX->defaultTo('1');

                $roverStartingPointY = $cli->input('Rover strating point Y coordinate (1 to 32):');
                $roverStartingPointY->accept(function ($y) {
                    if ((int) $y >= 1 && (int) $y <= 32) {
                        return true;
                    }
                    return false;
                });
                $roverStartingPointY->defaultTo('1');

                $direction = $cli->radio('Direction:', [
                    Direction::NORTH => 'North',
                    Direction::SOUTH => 'South',
                    Direction::EAST => 'East',
                    Direction::WEST => 'West'
                ]);

                $sequence = $cli->input('Sequence (use a combination of only lowercase f to move forward, l to rotate left and r to rotate right):');
                $sequence->accept(function ($response) {
                    return (preg_match('/^[FfLlRr]+$/', $response) === 1);
                });

                $nonInteractiveInput = [
                    'starting' => [
                        'x' => $roverStartingPointX->prompt(),
                        'y' => $roverStartingPointY->prompt()
                    ],
                    'direction' => $direction->prompt(),
                    'sequence' => str_split($sequence->prompt()),
                ];

                self::$nonInteractiveInput = $nonInteractiveInput;

                return $nonInteractiveInput;
            } else {
                self::$interactive = true;
            }
        }
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return mixed
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return string
     */
    public function getChar(): string
    {
        readline_callback_handler_install('', function () {
        });
        $stdin = fopen('php://stdin', 'r');
        stream_set_blocking($stdin, true);
        $char = fread($stdin, 1);
        //readline_callback_handler_remove();

        return $char;
    }
}
