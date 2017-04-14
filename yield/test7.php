<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-4-14
 * Time: 上午11:47
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

class SystemCall {
    protected $callback;

    public function __construct(callable $callback) {
        $this->callback = $callback;
    }

    public function __invoke(Task $task, Scheduler $scheduler) {
        $callback = $this->callback;
        return $callback($task, $scheduler);
    }
}




