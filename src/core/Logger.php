<?php

abstract class Logger {

    const LOGFILE          = 'errors.log';
    const LOG_PATH         = ROOT . '/logs';
    const LOGFILE_FULLPATH = self::LOG_PATH . '/' . self::LOGFILE;
    const MAXSIZE          = 10; // MB

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
            $name    = get_class($error);
            $message = preg_replace('/\s+/', ' ', $error->getMessage());
            $code    = $error->getCode();
            $file    = $error->getFile();

            return $error . ' ' . $code . ': ' . $message . ' (' . $file . ')';
        }

        return preg_replace('/\s+/', ' ', $error);
    }

    ///////////////////////////////////////////////////////////////////////////////
    private static function openLogFile() {
        // if there's a file with the same name as the folder, remove it
        if (file_exists(self::LOG_PATH) && ! is_dir(self::LOG_PATH)) {
            if (@unlink(SELF::LOG_PATH) === false) {
                throw new Exception('File with same name as log folder already exists and cannot be removed.');
            }
        }

        // try to create log folder if missing
        if ( ! file_exists(self::LOG_PATH)) {
            if (@mkdir(self::LOG_PATH) === false) {
                throw new Exception('Log folder is missing and cannot be created.');
            }
        }

        $fileName      = self::LOGFILE_FULLPATH;
        $maxSizeBytes  = self::MAXSIZE * 1024 * 1024;

        $i             = 0;
        $pathinfo      = pathinfo($fileName);

        // if the file exists and its size exceeds $maxSize, write to a new file using $i counter
        while (file_exists($fileName) && filesize($fileName) >= $maxSizeBytes) {
            $fileName = self::LOG_PATH . '/' . $pathinfo['filename'] . ++$i . '.' . $pathinfo['extension'];
        }

        // try to open log file for writing
        if ( ($file = @fopen($fileName, 'a')) === false) {
            throw new Exception('Log file does not exist or has no write permissions.');
        }

        return $file;
    }

    ///////////////////////////////////////////////////////////////////////////////
    private static function writeToFile($file = NULL, string $message) {
        if ($file) {
            $httpCode = http_response_code();
            $date     = date('Y-m-d H:i:s');
            $url      = rawurldecode($_SERVER['REQUEST_URI']);
            $ip       = $_SERVER['REMOTE_ADDR'];
            
            $message  = '[' . $date . '] ::: ' . (HOST_URL . $url) . ' (' . $httpCode . ') ::: ' . $message . " (" . $ip . ")\n";
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