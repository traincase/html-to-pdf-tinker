<?php

namespace Traincase\HtmlToPdfTinker;

use Closure;
use InvalidArgumentException;
use Traincase\HtmlToPdfTinker\Drivers\Driver;

class PdfTinkerManager
{
    private array $registeredDrivers = [];

    /**
     * Register a custom driver, based on a closure.
     *
     * @param string $driver
     * @param Closure $callback
     * @return $this
     */
    public function extend($driver, Closure $callback)
    {
        $this->registeredDrivers[$driver] = $callback;

        return $this;
    }

    /**
     * Resolve a registered driver
     *
     * @param string $driver
     * @return Driver
     */
    public function resolve(string $driver): Driver
    {
        if (isset($this->registeredDrivers[$driver])) {
            return $this->registeredDrivers[$driver]();
        }

        throw new InvalidArgumentException(sprintf('Driver "%s" is not supported.', $driver));
    }

    /**
     * The names of the registered drivers
     *
     * @return array
     */
    public function getRegisteredDrivers(): array
    {
        return array_keys($this->registeredDrivers);
    }
}
