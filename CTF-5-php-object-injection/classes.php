<?php

class UserSession
{
    public $username;
    public $is_admin;

    public function __construct($username = 'guest', $is_admin = false)
    {
        $this->username = $username;
        $this->is_admin = $is_admin;
    }
}

class FileLogger
{
    public $logFile;
    public $message;

    public function __construct($logFile = null, $message = null)
    {
        $this->logFile = $logFile;
        $this->message = $message;
    }

    public function __destruct()
    {
        if ($this->logFile !== null && $this->message !== null) {
            file_put_contents($this->logFile, $this->message . "\n", FILE_APPEND);
        }
    }
}

?>


