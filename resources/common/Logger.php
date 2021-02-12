<?php

class Logger {

    const LOGFILE          = 'errors.log';
    const LOG_PATH         = ROOT . '/logs';
    const LOGFILE_FULLPATH = self::LOG_PATH . '/' . self::LOGFILE;

    ///////////////////////////////////////////////////////////////////////////////
    public static function logError($error) {
        $error = self::getActualErrorMessage($error);
        $file  = self::openLogFile();
        self::writeToFile($file, $error);
        self::closeFile($file);
    }

    ///////////////////////////////////////////////////////////////////////////////
    private static function getActualErrorMessage($error) {

        // if the error is actually an exception,
        // get the important information out of it and concatenate it into a string
        if ($error instanceof Exception) {
            $message = preg_replace('/\s+/', ' ', $error->getMessage());
            $code    = $error->getCode();
            $file    = $error->getFile();

            return $code . ': ' . $message . ' (' . $file . ')';
        }

        return $error;
    }

    ///////////////////////////////////////////////////////////////////////////////
    private static function openLogFile() {
        // try to create log folder if missing
        if ( ! file_exists(self::LOG_PATH)) {
            if (@mkdir(self::LOG_PATH) === false) {
                throw new Exception('Log folder is missing and cannot be created.');
            }
        }

        // try to open log file for writing
        if ( ($file = @fopen(self::LOGFILE_FULLPATH, 'a')) === false) {
            throw new Exception('Log file does not exist or has no write permissions.');
        }

        return $file;
    }

    ///////////////////////////////////////////////////////////////////////////////
    private static function writeToFile($file = NULL, string $message) {
        if ($file) {
            $httpCode = http_response_code();
            $date     = date('Y-m-d H:i:s');
            $url      = $_SERVER['REQUEST_URI'];
            
            $message  = '[' . $date . '] ::: ' . (HOST_URL . $url) . ' (' . $httpCode . ') ::: ' . $message . "\n";
            fwrite($file, $message);
        }
    }

    ///////////////////////////////////////////////////////////////////////////////
    private static function closeFile($file = NULL) {
        if ($file) {
            fclose($file);
        }
    }
}