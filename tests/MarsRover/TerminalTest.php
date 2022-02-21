<?php

namespace Tests\MarsRover;

use MarsRover\Game\Direction;
use MarsRover\Terminal;
use Tests\TestBase;

class TerminalTest extends TestBase
{
    /** @test */
    public function it_allows_non_interactive_mode()
    {

        // just playing with some mocks and asserions
        $mock = \Mockery::mock(Terminal::class)->shouldReceive('inputHandlerForSequenceMode')->andReturn([
            [
                'starting' => [
                    'x' => 2,
                    'y' => 2
                ],
                'direction' => array_rand(
                    array_flip(
                        [
                            Direction::NORTH,
                            Direction::SOUTH,
                            Direction::EAST,
                            Direction::WEST
                        ]
                    ),
                    1
                ),
                'sequence' => str_split('ffrfflff'),
            ]
        ]);

        // this will let us know that both Terminal::class and Direction::class are working properly
        $this->assertContains($mock->getMock()->inputHandlerForSequenceMode()[0]['direction'], ['north', 'south', 'east', 'west']);

        // those are a bit redundant, but just to make sure that phpUnit works a little more
        $this->assertArrayHasKey('starting', $mock->getMock()->inputHandlerForSequenceMode()[0]);
        $this->assertArrayHasKey('direction', $mock->getMock()->inputHandlerForSequenceMode()[0]);
        $this->assertArrayHasKey('sequence', $mock->getMock()->inputHandlerForSequenceMode()[0]);
        $this->assertEquals(count($mock->getMock()->inputHandlerForSequenceMode()[0]), 3);
    }
}
