<?php

namespace SimpleThreads;

/**
 * Class Pool
 *
 * @package SimpleThreads
 */
class Pool
{
    /** @var array $commands */
    private $commands = [];

    /** @var array $threads */
    private $threads = [];

    /** @var array $output */
    private $output = [];


    /**
     * Pool constructor.
     *
     * @param array $commands
     */
    public function __construct(array $commands) {
        $this->commands = $commands;
    }

    /**
     * run
     */
    public function run() {

        foreach($this->commands as $key => $cmd) {

            $this->threads[] = new Thread("workers/".$cmd['command'].".php", $cmd['payload']);

            $outputObject = new \stdClass();
            $outputObject->start = time();
            $outputObject->id = $cmd['id'];
            $outputObject->command = $cmd['command'];
            $outputObject->payload = $cmd['payload'];
            $outputObject->error = false;

            $this->output[$key] = $outputObject;

        }

        $threadsActive = true;

        do {
            $threadStatus = [];

            foreach($this->threads as $key => $thread) {
                $rawResult = $thread->getContents();
                $result = @unserialize($rawResult);
                if ($result === false) { $result = new \stdClass(); $this->output[$key]->error = true; }
                $this->output[$key]->output = $result;
                $this->output[$key]->raw_output = $rawResult;
                $threadStatus[$key] = $thread->isThreadBusy();
            }

            //are all threads done?
            if(in_array(true, $threadStatus, true) === true){
            } else {
                $threadsActive = false;
            }

        } while ($threadsActive);
    }

    /**
     * getOutput
     *
     * @return array
     */
    public function getOutput() {
        return $this->output;
    }

}