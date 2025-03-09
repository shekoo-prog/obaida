<?php

namespace App\Core\Logger;

trait Loggable
{
    protected $logger;

    protected function initLogger()
    {
        $this->logger = new Logger();
    }

    protected function logError($message, $context = [])
    {
        if (!$this->logger) {
            $this->initLogger();
        }
        $this->logger->error($message, $context);
    }

    protected function logActivity($message, $context = [])
    {
        if (!$this->logger) {
            $this->initLogger();
        }
        $this->logger->activity($message, $context);
    }
}