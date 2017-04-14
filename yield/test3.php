<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-4-13
 * Time: 下午5:40
 */


/**
 * @param $start
 * @param $end
 * @return Generator
 */
function xRange($start, $end)
{
    for ($i = $start; $i < $end; $i++) {

        # a
        yield $i;   //yield 在变量前，做为语句使用，返回Generator

        # b
        $b = yield;
        echo '<<<<<<' . $b . ' -' . gettype($b) . '>>>>>>' . PHP_EOL;   //yield 做为表达式，接收send的值
    }
}

$a = xRange(1, 10);
//var_dump($a);   //Generator

var_dump($a->current()); // 1   在a处挂起

$a->next();             //在b处挂起，$b 赋值 之前
var_dump($a->current()); // null  ,原因是，该yield 不是做为语句使用，而是表达式，不会返回一个值


$a->next();             //$b 赋值，然后echo 继续执行echo，由于 next 之前没有send ，则yield 接收了一个 null
var_dump($a->current());    //2


$a->next();             //在b处挂起$b 赋值 之前
var_dump($a->send('test'));     //test -string， ,在a 处挂起 3
var_dump($a->current());        //，没有 next 还是 3,

$a->next(); //在b处挂起，$b 赋值 之前
var_dump($a->current());    //null
var_dump($a->current());    //null
var_dump($a->current());    //null

$a->next(); //echo null -null
var_dump($a->current());    //4

var_dump($a->send('test2'));    //null

var_dump($a->send('test3'));    //echo test3 -string 5

var_dump($a->current());

/*var_dump($a->send('123'));


var_dump($a->send('234'));*/