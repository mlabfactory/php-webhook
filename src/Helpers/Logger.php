<?php 
namespace Mlab\Webhook\Helpers;

use Illuminate\Support\Facades\Facade;
use Monolog\Logger as MonologLogger;

/**
 * Logger Class
 * 
 * A final class that provides logging functionality.
 * This class cannot be extended and serves as a standalone logging utility.
 * 
 * @method static void emergency($message, array $context = [])
 * @method static void alert($message, array $context = [])
 * @method static void critical($message, array $context = [])
 * @method static void error($message, array $context = [])
 * @method static void warning($message, array $context = [])
 * @method static void notice($message, array $context = [])
 * @method static void info($message, array $context = [])
 * @method static void debug($message, array $context = [])
 * 
 * @see Monolog\Logger
 * 
 * @package MLAB\Helpers
 * @final
 */
final class Logger extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'log';
    }
    
}