<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Application;
use Piwik\Common;

/**
 * TODO
 *
 * TODO:
 * - remove StaticContainer (get from topmost application)
 *   * use TrackerApplication in LocalTracker
 * - create Console Application
 * - create Web Application
 * - BENCHMARK, BENCHMARK, BENCHMARK!
 *
 * TODO: applications should have shutdown logic so memory is cleaned up when using an Application class inside
 *       execution of another. perhaps there could be a Application::doWithApp($callback) method that creates/cleans up
 *       an application.
 */
abstract class Application
{
    /**
     * @var Environment
     */
    private $environment;

    public function __construct(Environment $environment)
    {
        $environment->init();

        $this->environment = $environment;
        $this->environment->getContainer()->set('Piwik\Application\Application', $this);
    }

    /**
     * TODO
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    public function __destruct()
    {
        $this->environment->__destruct();
    }

    /**
     * TODO
     *
     * @param callable $callback
     * @return mixed
     * @throws \Exception
     */
    public static function doWithApp($callback)
    {
        $applicationClass = get_called_class();

        /** @var Application $application */
        $application = new $applicationClass();

        $caughtException = null;
        $result = null;

        try {
            $result = $callback($application);
        } catch (\Exception $ex) {
            $caughtException = $ex;
        }

        Common::destroy($application);

        if ($caughtException !== null) {
            throw $caughtException;
        }

        return $result;
    }
}