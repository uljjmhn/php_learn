<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-4-13
 * Time: 下午5:30
 */

/**
 * @param $fileName
 */
function logger($fileName)
{
    $handle = fopen($fileName, 'a');
    while (true) {
        fwrite($handle, yield . "\n");
    }
}

/** @var Generator $generator */
$generator = logger(__DIR__ . '/test2.log');
$generator->send('hello');
$generator->send('how');
$generator->send('are');
$generator->send('you');


//yield 做为接收者，

//$generator = logger('test2.log'); //程序运行到yield 那里，被挂起，返回一个 Generator
//Generator send 一个数据过去，在yield 挂起那里，调用send 的值并返回给fwrite ，然后进入下一次循环。。。