<?php

/**
 * @brief 多任务协作 多任务协作 多任务协作
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-4-14
 * Time: 上午9:36
 */
class Task
{
    protected $taskId;

    /**
     * @var Generator
     */
    protected $coroutine = null;

    protected $sendValue;

    protected $beforeFirstYield = true;

    public function __construct($taskId, Generator $coroutine)
    {
        $this->taskId    = $taskId;
        $this->coroutine = $coroutine;
    }

    public function getTaskId()
    {
        return $this->taskId;
    }

    public function setSendValue($sendValue)
    {
        $this->sendValue = $sendValue;
    }

    public function run()
    {
        if ($this->beforeFirstYield) {
            $this->beforeFirstYield = false;
            return $this->coroutine->current();
        } else {
            $retVal          = $this->coroutine->send($this->sendValue);
            $this->sendValue = null;
            return $retVal;
        }
    }

    public function isFinished()
    {
        return !$this->coroutine->valid();
    }

}


//
class Scheduler
{
    protected $maxTaskId = 0;

    protected $taskMap = [];    //taskId=>task

    protected $taskQueue;

    public function __construct()
    {
        $this->taskQueue = new SplQueue();
    }

    public function schedule(Task $task)
    {
        $this->taskQueue->enqueue($task);
    }

    public function newTask(Generator $coroutine)
    {
        $taskId = ++$this->maxTaskId;
        $task = new Task($taskId, $coroutine);
        $this->taskMap[$taskId] = $task;
        $this->schedule($task);
        return $taskId;
    }

    public function run()
    {
        while (!$this->taskQueue->isEmpty()) {
            /** @var Task $task */
            $task = $this->taskQueue->dequeue();
            $task->run();

            if($task->isFinished()){
                unset($this->taskMap[$task->getTaskId()]);
            }else{
                $this->schedule($task);
            }
        }
    }
}

function task1() {
    for ($i = 1; $i <= 10; ++$i) {
        echo "This is task 1 iteration $i.\n";
        yield;
    }
}

function task2() {
    for ($i = 1; $i <= 5; ++$i) {
        echo "This is task 2 iteration $i.\n";
        yield;
    }
}

function task3() {
    for ($i = 1; $i <= 5; ++$i) {
        echo "This is task 3 iteration $i.\n";
        yield;
    }
}


$scheduler = new Scheduler;

$scheduler->newTask(task1());
$scheduler->newTask(task2());
$scheduler->newTask(task3());


$scheduler->run();