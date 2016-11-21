<?php
/**
 * @author a.itsekson
 * @createdAt: 15.07.2016 22:35
 */

namespace Icekson\Utils;


class ConsoleTextColorizator
{
    const COLOR_RED = 'red';
    const COLOR_BLACK = 'black';
    const COLOR_DARK_GRAY = 'dark_gray';
    const COLOR_LIGHT_RED = 'light_red';
    const COLOR_GREEN = 'green';
    const COLOR_LIGHT_GREEN = 'light_green';
    const COLOR_ORANGE = 'orange';
    const COLOR_YELLOW = 'yellow';
    const COLOR_BLUE = 'blue';
    const COLOR_PURPLE = 'purple';
    const COLOR_LIGHT_GRAY = 'light_gray';
    const COLOR_WHITE = 'white';

    private $colors = [
        'red' => "\033[0;31m",
        'black' =>  "\033[0;30m",
        'dark_gray' =>  "\033[1;30m",
        'light_red' => "\033[1;31m",
        'green' => "\033[0;32m",
        'light_green' => "\033[1;32m",
        'orange' => "\033[0;33m",
        'yellow' => "\033[1;33m",
        'blue'   => "\033[0;34m" ,
        'purple' => "\033[0;35m",
        'light_gray' => "\033[0;37m",
        'white' => "\033[1;37m"
    ];

    private $end = "\033[0m";

    public function wrap($text, $color){
        return sprintf("%s%s%s", $this->colors[$color], $text, $this->end);
    }
}