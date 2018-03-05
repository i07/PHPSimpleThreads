<?php

namespace SimpleThreads\Interfaces;

interface IWorkerFactory
{
    public function doWork($arguments);
}