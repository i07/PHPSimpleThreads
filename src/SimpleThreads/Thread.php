<?php

namespace SimpleThreads;

class Thread
{
    private $thread = null;
    private $pipes = [];

    private $spec = array(
        0 => array("pipe", "r"),
        1 => array("pipe", "w"),
        2 => array("pipe", "w")
    );

    public function __construct($command, $payload) {
        $this->thread = proc_open('php '.$command, $this->spec, $this->pipes, getcwd());
        if (is_resource($this->thread)) {
            fwrite($this->pipes[0], serialize($payload));
        }
    }

    public function isThreadBusy() {
        usleep(10000);
        $status = proc_get_status($this->thread);

        if ($status["running"]) {
            return true;
        } else {
            fclose($this->pipes[0]);
            fclose($this->pipes[1]);
            return false;
        }
    }

    public function getContents() {
        return stream_get_contents($this->pipes[1]);
    }
}