<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Application;

/**
 * TODO
 *
 * TODO:
 *   * use TrackerApplication in LocalTracker
 * - create Console Application
 * - create Web Application
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
}