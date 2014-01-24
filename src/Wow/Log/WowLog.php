<?php namespace Wow\Log;
/**
 * PHP WowLog
 *
 * PHP version 5
 *
 * @category Wow
 * @package  WowLog
 * @author   Tzeng, Yi-Feng <yftzeng@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/yftzeng/phpWowLog
 */

/**
 * PHP WowLog
 *
 * @category Wow
 * @package  WowLog
 * @author   Tzeng, Yi-Feng <yftzeng@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/yftzeng/phpWowLog
 */
class WowLog
{
    /**
     * @var int
     * @comment overall severity level
     */
    const OFF    = 9;
    const EMER   = 8;
    const ALERT  = 7;
    const CRIT   = 6;
    const ERROR  = 5;
    const WARN   = 4;
    const NOTICE = 3;
    const INFO   = 2;
    const DEBUG  = 1;

    /**
     * @var boolean|string
     * @comment start execution time
     */
    private static $_startTime    = false;

    /**
     * @var boolean|string
     * @comment previous execution time
     */
    private static $_previousTime = false;

    /**
     * @var boolean|floatval
     * @comment previous cpu time
     */
    private static $_previousCpuTime = false;

    /**
     * @var string
     * @comment severity threshold level
     */
    private static $_severityThreshold = self::INFO;

    /**
     * @var null|string
     * @comment log dir
     */
    private static $_logDir           = null;

    /**
     * @param string $severity severity level
     *
     * @comment Map Severity Name to Int
     *
     * @return int
     */
    private static function _mapSeverityNameToInt($severity)
    {
        /*
         * HACK: if() is a bit faster than switch()
         *       but switch() is more code readability :(
         */
        if ($severity === 'EMER') {
            return self::EMER;
        } else if ($severity === 'ALERT') {
            return self::ALERT;
        } else if ($severity === 'CRIT') {
            return self::CRIT;
        } else if ($severity === 'ERROR') {
            return self::ERROR;
        } else if ($severity === 'WARN') {
            return self::WARN;
        } else if ($severity === 'NOTICE') {
            return self::NOTICE;
        } else if ($severity === 'INFO') {
            return self::INFO;
        } else if ($severity === 'DEBUG') {
            return self::DEBUG;
        } else {
            return self::OFF;
        }
    }

    /**
     * @param string $logDir   Log directory
     * @param int    $severity severity level
     *
     * @comment init
     *
     * @return void
     */
    public static function init($logDir, $severity = self::INFO)
    {
        if ($severity === self::OFF) {
            return;
        }

        self::$_logDir = rtrim($logDir, '\\/');

        if (!is_numeric($severity)) {
            $severity = self::_mapSeverityNameToInt($severity);
        }
        self::$_severityThreshold = $severity;


        self::$_startTime = microtime(true);
    }

    /**
     * @param string $message  log message
     * @param string $logClass log class
     *
     * @comment debug level message
     *
     * @return void
     */
    public static function debug($message, $logClass = '')
    {
        self::_log($message, self::DEBUG, $logClass);
    }

    /**
     * @param string $message  log message
     * @param string $logClass log class
     *
     * @comment info level message
     *
     * @return void
     */
    public static function info($message, $logClass = '')
    {
        self::_log($message, self::INFO, $logClass);
    }

    /**
     * @param string $message  log message
     * @param string $logClass log class
     *
     * @comment notice level message
     *
     * @return void
     */
    public static function notice($message, $logClass = '')
    {
        self::_log($message, self::NOTICE, $logClass);
    }

    /**
     * @param string $message  log message
     * @param string $logClass log class
     *
     * @comment warn level message
     *
     * @return void
     */
    public static function warn($message, $logClass = '')
    {
        self::_log($message, self::WARN, $logClass);
    }

    /**
     * @param string $message  log message
     * @param string $logClass log class
     *
     * @comment error level message
     *
     * @return void
     */
    public static function error($message, $logClass = '')
    {
        self::_log($message, self::ERROR, $logClass);
    }

    /**
     * @param string $message  log message
     * @param string $logClass log class
     *
     * @comment alert level message
     *
     * @return void
     */
    public static function alert($message, $logClass = '')
    {
        self::_log($message, self::ALERT, $logClass);
    }

    /**
     * @param string $message  log message
     * @param string $logClass log class
     *
     * @comment crit(ical) level message
     *
     * @return void
     */
    public static function crit($message, $logClass = '')
    {
        self::_log($message, self::CRIT, $logClass);
    }

    /**
     * @param string $message  log message
     * @param string $logClass log class
     *
     * @comment emer(gency) level message
     *
     * @return void
     */
    public static function emer($message, $logClass = '')
    {
        self::_log($message, self::EMER, $logClass);
    }

    /**
     * @comment get previous object for trace its __FILE__ and __LINE__
     *
     * @return object
     */
    private static function _getPreObject()
    {
        $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        return $bt[count($bt)-1];
    }

    /**
     * @param string $severity severity level
     * @param string $file     calling filename
     * @param string $function calling function
     * @param string $line     calling filename's line
     *
     * @comment prepare log format
     *
     * @return string
     */
    private static function _getLogFormat($severity, $file, $function, $line)
    {
        if (self::$_previousTime === false) {
            $recordTime = microtime(true) - self::$_startTime;
        } else {
            $recordTime = microtime(true) - self::$_previousTime;
        }
        self::$_previousTime = microtime(true);

        $format
            = '[' . date('Y-m-d H:i:s') . ']['
            . sprintf("%f", $recordTime) . '][Mem:'
            . memory_get_usage(true) . ']['
            . $file . ' :' . $function . ' :'. $line . ']';

        /*
         * HACK: if() is a bit faster than switch()
         *       but switch() is more code readability :(
         */
        if ($severity === self::EMER) {
            return $format .= '(EMER) ';
        } else if ($severity === self::ALERT) {
            return $format .= '(ALERT) ';
        } else if ($severity === self::CRIT) {
            return $format .= '(CRIT) ';
        } else if ($severity === self::ERROR) {
            return $format .= '(ERROR) ';
        } else if ($severity === self::WARN) {
            return $format .= '(WARN) ';
        } else if ($severity === self::NOTICE) {
            return $format .= '(NOTICE) ';
        } else if ($severity === self::INFO) {
            return $format .= '(INFO) ';
        } else if ($severity === self::DEBUG) {
            return $format .= '(DEBUG) ';
        } else {
            return $format .= ' ';
        }
    }

    /**
     * @param string $message  log message
     * @param string $severity severity level
     * @param string $logClass log class
     *
     * @comment log operation
     *
     * @return void
     */
    private static function _log($message, $severity, $logClass = '')
    {
        $_logDir = self::$_logDir . DIRECTORY_SEPARATOR . $logClass;
        if (!file_exists($_logDir)) {
            mkdir($_logDir, 0777, true);
        }

        $_logFile = $_logDir . DIRECTORY_SEPARATOR . date('Y-m-d') . '.log';

        if (self::$_severityThreshold <= $severity) {
            $bt = self::_getPreObject();

            /**
             * HACK: error_log() is faster than fwrite() and file_put_contents()
             */

            if (is_array($message)) {
                $message = print_r($message, true);
            }
            error_log(
                self::_getLogFormat(
                    $severity, $bt['file'], $bt['function'], $bt['line']
                ) . $message . PHP_EOL,
                3,
                $_logFile
            );
        }
    }
}
