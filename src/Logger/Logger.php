<?php

namespace Logger;

use Exception;

class Logger
{
  static public $staffName;
  static public $dir = 'log';
  static public $errorLogFilename = 'error.log';
  static public $infoLogFilename = 'info.log';
  // static public $infoLogFilename = 'info.log';

  static function checkDir()
  {
    $logDir = $_SERVER['DOCUMENT_ROOT'] . '/' . Logger::$dir;
    $logDir = str_replace('/', DIRECTORY_SEPARATOR, $logDir);
    $logDir = str_replace('\\', DIRECTORY_SEPARATOR, $logDir);
    if (!is_dir($logDir)) {
      mkdir($logDir);
    }
  }

  static function message(string $msg, string $mode = 'info')
  {
    Logger::checkDir();
    switch ($mode) {
      case 'info':
        $logFile = Logger::$infoLogFilename;
        break;
      case 'error':
        $logFile = Logger::$errorLogFilename;
        break;
      default:
        throw new Exception(message: "Invalid log mode provided. Select between error and info");
    }
    $logFile = str_replace('/', DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT'] . '/log/' . $logFile);
    $logFile = str_replace('\\', DIRECTORY_SEPARATOR, $logFile);

    $message = "Date: [" . date('Y-m-d h:i:sA') . "] \n";
    $message .= "Logged by: {" . Logger::$staffName . "} \n";
    $message .= "Message: $msg.\n\n";

    $file = fopen($logFile, 'a');
    fwrite($file, $message);
    fclose($file);
  }
}
