<?php

namespace SimpleThreads\Factory;

abstract class WorkerFactory
{
    public function __construct($description)
    {
        // read data for worker
        $arguments = fread(STDIN,4096);
        $arguments = unserialize($arguments);

        $obj = new \stdClass();
        $obj->start = time();
        $obj->pid = getmypid();
        $obj->data = $description;

        $this->doWork($arguments);

        $obj->duration = time()-$obj->start;
        $obj->end = time();

        echo serialize($obj);

    }

    public function doWork($arguments) {
        echo "you didn't extend me.";
    }
}