<?php

/**
 * Return the evaluated value of a value (ya, that's right).
 *
 * @param       $value
 * @param array $arguments
 * @return mixed|null
 */
function evaluate($value, array $arguments = [])
{
    if ($value instanceof \Closure) {

        try {

            return app()->call($value, $arguments);
        } catch (\Exception $e) {

            return null;
        }
    } elseif (is_array($value)) {

        foreach ($value as &$val) {

            $val = evaluate($val, $arguments);
        }
    }

    if (is_string($value) && str_contains($value, '{{')) {

        $value = view()->parse($value, $arguments)->render();
    }

    return $value;
}

/**
 * Return the evaluated value of an array key.
 * If no key exists return the default value.
 *
 * @param       $array
 * @param       $key
 * @param null  $default
 * @param array $arguments
 * @return mixed|null
 */
function evaluate_key($array, $key, $default = null, $arguments = [])
{
    return is_array($array) ? evaluate(key_value($array, $key, $default), $arguments) : $default;
}

/**
 * Return the value of an array.
 * If no key exists return the default value.
 *
 * @param array $array
 * @param       $key
 * @param null  $default
 * @return null
 */
function key_value(array $array, $key, $default = null)
{
    return isset($array[$key]) ? $array[$key] : $default;
}

/**
 * Return the current memory usage.
 *
 * @return string
 */
function memory_usage()
{
    $unit = array('b', 'kb', 'mb');

    $size = memory_get_usage(true);

    return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}

/**
 * Return the elapsed request time.
 *
 * @return mixed
 */
function request_time()
{
    return number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 2) . ' s';
}

/**
 * Return the HTTP_REFERER or fallback.
 *
 * @return string
 */
function referer($fallback = null)
{
    if (!$fallback) {

        $fallback = url();
    }

    return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $fallback;
}

/**
 * Return the streams package path.
 *
 * @param null $path
 * @return string
 */
function streams_path($path = null)
{
    return dirname(__DIR__) . ($path ? '/' . $path : null);
}

/**
 * Determine if a string is translatable.
 *
 * This is helpful for defaulting and
 * cascading translatable strings.
 *
 * @param $string
 * @return bool
 */
function is_translatable($string)
{
    $translated = trans($string);

    return ($string !== $translated);
}

/**
 * Return the client's IP address.
 *
 * @return mixed
 */
function ip()
{
    return app('request')->ip();
}

/**
 * Determine if we have a lotto winner.
 *
 * @param  array $lottery [2, 100]
 * @return bool
 */
function hit(array $lottery = [2, 100])
{
    return mt_rand(1, $lottery[1]) <= $lottery[0];
}

/**
 * Return an array parsed into a string of attributes.
 *
 * @param array $attributes
 * @return string
 */
function attributes_string(array $attributes)
{
    return implode(
        ' ',
        array_map(
            function ($v, $k) {

                return $k . '=' . '"' . trans($v) . '"';
            },
            $attributes,
            array_keys($attributes)
        )
    );
}

/**
 * Get the path to the assets folder.
 *
 * @param  string $path
 * @return string
 */
function assets_path($path = '')
{
    return public_path('assets/' . APP_REF . ($path ? '/' . $path : $path));
}