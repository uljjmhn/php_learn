<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-4-13
 * Time: 下午5:30
 */

class TestYield
{
    function xRange($start, $end, $step=1)
    {
        for ($i = $start; $i < $end; $i += $step) {
            var_dump('???????');
            yield $i;
        }
    }

}


$test  = new TestYield();
/*foreach ($test->xRange(1,10) as $num) {
    echo $num.PHP_EOL;
}*/

/** @var Generator $a */
$a = $test->xRange(1, 5);
var_dump($a->current());
$a->next();
var_dump($a->current());
$a->next();
/*
var_dump($a->current());
var_dump($a->current());
var_dump($a->current());*/
