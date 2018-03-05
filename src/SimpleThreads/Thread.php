<?php

namespace SimpleThreads;

/**
 * Class Thread
 *
 * @package SimpleThreads
 */
class Thread
{
    /** @var bool|null|resource $thread */
    private $thread = null;

    /** @var array $pipes */
    private $pipes = [];

    /** @var array $spec */
    private $spec = array(
        0 => array("pipe", "r"),
        1 => array("pipe", "w"),
        2 => array("pipe", "w")
    );

    /**
     * Thread constructor.
     *
     * @param $command
     * @param $payload
     */
    public function __construct(string $command, mixed $payload) {
        $this->thread = proc_open('php '.$command, $this->spec, $this->pipes, getcwd());

        if (is_resource($this->thread)) {
            fwrite($this->pipes[0], serialize($payload));
        }
    }

    /**
     * isThreadBusy
     *
     * @return bool
     */
    public function isThreadBusy() {

        // put a little break on the isThreadBusy to spare some cpu cycles
        usleep(10000);

        // get process status
        $status = proc_get_status($this->thread);

        if ($status["running"]) {
            // still running
            return true;
        } else {
            // no longer running, we clean up
            fclose($this->pipes[0]);
            fclose($this->pipes[1]);
            return false;
        }
    }

    /**
     * getContents
     *
     * @return bool|string
     */
    public function getContents() {
        return stream_get_contents($this->pipes[1]);
    }
}