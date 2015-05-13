<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Container;

use DI\Container;

/**
 * This class provides a static access to the container.
 *
 * @deprecated This class is introduced only to keep BC with the current static architecture. It will be removed in 3.0.
 *     - it is global state (that class makes the container a global variable)
 *     - using the container directly is the "service locator" anti-pattern (which is not dependency injection)
 */
class StaticContainer
{
    /**
     * @var Container[]
     */
    private static $containers;

    /**
     * Definitions to register in the container.
     *
     * @var array
     */
    private static $definitions = array();

    /**
     * @return Container
     */
    public static function getContainer()
    {
        if (empty(self::$containers)) {
            throw new \Exception("The root container has not been created yet.");
        }

        return end(self::$containers);
    }

    public static function clearContainer() //TODO: remove this method
    {
        throw new \Exception("is this still used? (StaticContainer::clearContainer())");
    }

    public static function addDefinitions(array $definitions)
    {
        self::$definitions = $definitions;
    }

    /**
     * Proxy to Container::get()
     *
     * @param string $name Container entry name.
     * @return mixed
     * @throws \DI\NotFoundException
     */
    public static function get($name)
    {
        return self::getContainer()->get($name);
    }

    public static function getDefinitions()
    {
        return self::$definitions;
    }

    public static function push(Container $container)
    {
        self::$containers[] = $container;
        return count(self::$containers) - 1;
    }

    public static function pop($index)
    {
        unset(self::$containers[$index]);
    }
}
