<?php

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

if (! \function_exists('carbonize')) {
    /**
     * Parse string to Carbon instance.
     *
     * @param mixed  $datetime
     * @param string|null  $timezone
     *
     * @return \Carbon\CarbonInterface|null
     */
    function carbonize($datetime, ?string $timezone = 'UTC'): ?CarbonInterface
    {
        return \rescue(static function () use ($datetime, $timezone) {
            if ($datetime instanceof CarbonInterface) {
                return \use_timezone($datetime, $timezone ?? $datetime->timezone);
            } elseif ($datetime instanceof DateTimeInterface) {
                return Carbon::instance($datetime)->timezone($timezone);
            } elseif (\is_array($datetime) && isset($datetime['date'])) {
                return Carbon::parse($datetime['date'], $datetime['timezone'] ?? 'UTC');
            } elseif (\is_string($datetime)) {
                return Carbon::parse($datetime, $timezone);
            }
        }, null, false);
    }
}

if (! \function_exists('get_meta')) {
    /**
     * Get meta.
     *
     * @param  string  $key
     * @param  mixed   $default
     *
     * @return mixed
     */
    function get_meta(string $key, $default = null)
    {
        return \app('orchestra.meta')->get($key, $default);
    }
}

if (! \function_exists('handles')) {
    /**
     * Return handles configuration for a package/app.
     *
     * @param  string  $name
     * @param  array   $options
     *
     * @return string
     */
    function handles(string $name, array $options = []): string
    {
        return \app('orchestra.app')->handles($name, $options);
    }
}

if (! \function_exists('memorize')) {
    /**
     * Return memory configuration associated to the request.
     *
     * @param  string  $key
     * @param  mixed  $default
     *
     * @return mixed
     *
     * @see \Orchestra\Foundation\Foundation::memory()
     */
    function memorize(string $key, $default = null)
    {
        return \app('orchestra.platform.memory')->get($key, $default);
    }
}

if (! \function_exists('orchestra')) {
    /**
     * Return orchestra.app instance.
     *
     * @param  string|null  $service
     *
     * @return mixed
     */
    function orchestra(?string $service = null)
    {
        if (\is_null($service)) {
            return \app('orchestra.app');
        }

        return \app("orchestra.platform.{$service}");
    }
}

if (! \function_exists('set_meta')) {
    /**
     * Set meta.
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return mixed
     */
    function set_meta(string $key, $value = null)
    {
        return \app('orchestra.meta')->set($key, $value);
    }
}

if (! \function_exists('use_timezone')) {
    /**
     * Clone carbon and use different timezone.
     *
     * @param \Carbon\CarbonInterface  $carbon
     * @param string  $timezone
     *
     * @return \Carbon\CarbonInterface
     */
    function use_timezone(CarbonInterface $carbon, string $timezone): CarbonInterface
    {
        if ($carbon->tzName === $timezone) {
            return $carbon->copy();
        }

        return $carbon->copy()->timezone($timezone);
    }
}
