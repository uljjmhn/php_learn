<?php
/**
 * Created by PhpStorm.
 * User: debian
 * Date: 4/13/17
 * Time: 10:12 PM
 */


/**
 * @return Generator
 */
function gen()
{
    $ret1 = (yield 'yield1');
    var_dump('1');
    echo($ret1), PHP_EOL;

    $ret2 = (yield 'yield2');
    var_dump('2');
    echo($ret2), PHP_EOL;
}

$gen = gen();


var_dump($gen->current());                  //在$ret1 赋值之前挂起，返回yield 后的值，
                                            //结果为 #string(6) "yield1"


var_dump($gen->send('ret1'));         //紧接上一步，yield接收先外部生成器send的值，然后赋值给$ret1，即$ret1='ret1'
                                            //然后按顺序执行 var_dump()  echo '' 到yield 挂起，返回yield 后的值 yield2
                                            //所以结果为
                                            //string(1) "1"         //var_dump('1');
                                            //ret1                  //echo($ret1), PHP_EOL;
                                            //string(6) "yield2"    //var_dump($gen->send('ret1'));



var_dump($gen->send('ret2'));         //执行顺序同上
                                            //把send的值赋给$ret2 即 $ret2 = 'ret2'
                                            //string(1) "2"         //var_dump('2');
                                            //ret2                  //echo($ret2), PHP_EOL;
                                            //null                  //var_dump($gen->send('ret2')); //因为gen函数没有yield和return了，所以为null

var_dump('-------------------------------------------------------------');

/**
 * @return Generator
 */
function gen1()
{
    $ret1 = (yield 'yield1');

    var_dump('1');
    echo($ret1), PHP_EOL;

    $ret2 = (yield 'yield2');
    var_dump('2');
    echo($ret2), PHP_EOL;
}

$gen1 = gen1();

var_dump($gen1->send('start'));     //$ret1 = 'start'   //var_dump('1') //echo '...' //var_dump($gen1->send('...'));

var_dump($gen1->send('ret1'));

var_dump($gen1->send('ret2'));      //已经执行完了