<?php

namespace SimpleThreads\Factory;

/**
 * Class WorkerFactory
 *
 * @package SimpleThreads\Factory
 */
abstract class WorkerFactory
{
    /**
     * @var mixed $arguments
     */
    public $arguments;

    /**
     * WorkerFactory constructor.
     *
     * @param string $description
     */
    public function __construct(string $description)
    {
        // read data for worker
        $this->arguments = fread(STDIN,4096);
        $this->arguments = unserialize($this->arguments);

        $obj = new \stdClass();
        $obj->start = time();
        $obj->pid = getmypid();
        $obj->description = $description;

        $obj->response = $this->doWork($this->arguments);

        $obj->duration = time()-$obj->start;
        $obj->end = time();

        echo serialize($obj);

    }

    /**
     * doWork
     *
     * @param mixed $arguments
     * @return mixed
     */
    public function doWork(mixed $arguments){
        return $arguments;
    }
}