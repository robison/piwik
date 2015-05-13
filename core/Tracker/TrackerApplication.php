<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Tracker;

use Piwik\Application\Application;
use Piwik\Tracker;

/**
 * TODO
 *
 * TODO: use in LocalTracker
 */
class TrackerApplication extends Application
{
    public function __construct($definitions = array())
    {
        parent::__construct(new TrackerEnvironment($definitions));
    }

    /**
     * TODO
     *
     * @param array|RequestSet|null $params
     * @return int
     */
    public function track($params = null)
    {
        $container = $this->getEnvironment()->getContainer();

        if (empty($params)) {
            $requestSet = $container->make('Piwik\Tracker\RequestSet');
        } else if ($params instanceof RequestSet) {
            $requestSet = $params;
        } else if (is_array($params)) {
            $requestSet = $container->make('Piwik\Tracker\RequestSet');
            $requestSet->setRequests(array($params));
        } else {
            throw new \InvalidArgumentException("Invalid argument '\$params' supplied to TrackerApplication::track().");
        }

        /** @var Tracker $tracker */
        $tracker = $this->getEnvironment()->getContainer()->get('Piwik\Tracker');

        try {
            $handler  = Handler\Factory::make(); // TODO: create from DI.
            $response = $tracker->main($handler, $requestSet);

            if (!is_null($response)) {
                echo $response; // TODO: shouldn't echo. instead there should be a response class that we can return w/ return code + response text
            }
        } catch (\Exception $e) {
            echo "Error:" . $e->getMessage();
            return 1;
        }

        return 0;
    }
}