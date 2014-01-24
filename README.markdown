# phpWowLog

Wow! Log for PHP. Simple but good enough.

## Requirement

PHP 5.3+

## Usage

Please check out "samples" directory.

## Format

```
[Time][Execution Time][__FILE__:__LINE__] Message
```

Example:

```
[2012-04-25 14:50:06][0.000043][SimpleLogger.php:7] start
[2012-04-25 14:50:06][0.000034][SimpleLogger.php:9] echo 1
[2012-04-25 14:50:07][1.000221][SimpleLogger.php:11] sleep 1
[2012-04-25 14:50:07][0.000060][SimpleLogger.php:13] echo abc
```

Especially, each log message has its own "execution time",
and which `__FILE__` and `__LINE__` did log.

## Otherwise

If you think [Monolog](https://github.com/Seldaek/monolog) is more better, you could also check out "others" directory, I *dirty* hacked it by adding execution time. (It's *dirty* so I did not feedback to upstream, happy hacking)

## License

the MIT License
