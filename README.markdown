# phpWowLog

Wow! Log for PHP. Simple but good enough.

## Requirement

PHP 5.3+

## Usage

### Standalone WowLog library

```
include '../src/Wow/Log/WowLog.php';

use Wow\Log\WowLog;

$log_dir = 'log';
$log_level = 'DEBUG';

WowLog::init($log_dir, $log_level);
WowLog::info('info');
WowLog::notice('notice');
WowLog::warn('warn');
WowLog::alert('alert');
WowLog::crit('alert');
WowLog::emer('alert');

# Change log_dir to 'anotherLogDir'
WowLog::info("start", 'anotherLogDir');
```

### Work with Composer

#### Edit `composer.json`

```
{
    "require": {
        "yftzeng/wowlog": "dev-master"
    }
}
```

#### Update composer

```
$ php composer.phar update
```

#### Sample code
```
include 'vendor/autoload.php';

use Wow\Log\WowLog;

$log_dir = 'log';
$log_level = 'DEBUG';

WowLog::init($log_dir, $log_level);
WowLog::info('info');
WowLog::notice('notice');
WowLog::warn('warn');
WowLog::alert('alert');
WowLog::crit('alert');
WowLog::emer('alert');

# Change log_dir to 'anotherLogDir'
WowLog::info("start", 'anotherLogDir');
```

## Format

```
[Time][Execution Time][Memory Usage][__FILE__:__LINE__] Message
```

Example:

```
[2012-04-25 14:50:06][0.000043][Mem:524288][SimpleLogger.php:7] start
[2012-04-25 14:50:06][0.000034][Mem:524288][SimpleLogger.php:9] echo 1
[2012-04-25 14:50:07][1.000221][Mem:524288][SimpleLogger.php:11] sleep 1
[2012-04-25 14:50:07][0.000060][Mem:524288][SimpleLogger.php:13] echo abc
```

Especially, each log message has its own "execution time", and which `__FILE__` and `__LINE__` did log.

## Otherwise

If you think [Monolog](https://github.com/Seldaek/monolog) is more better, you could also check out `patch` directory, I *dirty* hacked it by adding execution time. (It's *dirty* so I did not feedback to upstream, happy hacking)

## License

the MIT License
