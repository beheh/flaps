<?php
namespace BehEh\Flaps\Violation;

use BehEh\Flaps\ViolationHandlerInterface;

/**
 * Handles violations by sending the corresponding HTTP header and exiting.
 *
 * @since 0.1
 * @author Benedict Etzel <developer@beheh.de>
 */
class HttpViolationHandler implements ViolationHandlerInterface
{

    /**
     * Handles a violation by sending the corresponding HTTP header and exiting.
     */
    public function handleViolation()
    {
        $this->sendHeader();
        $this->callExit();
    }

    /**
     * @codeCoverageIgnore
     */
    protected function sendHeader()
    {
        header('HTTP/1.1 429 Too Many Requests');
        header('Content-Type: text/plain');
    }

    /**
     * @codeCoverageIgnore
     */
    protected function callExit()
    {
        die('Too many requests');
    }
}
