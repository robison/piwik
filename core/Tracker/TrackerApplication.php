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
        // TODO: get RequestSet from DI too.
        if (empty($params)) {
            $requestSet = new RequestSet();
        } else if ($params instanceof RequestSet) {
            $requestSet = $params;
        } else if (is_array($params)) {
            $requestSet = new RequestSet();
            $requestSet->setRequests(array($params));
        } else {
            throw new \InvalidArgumentException("Invalid argument '\$params' supplied to TrackerApplication::track().");
        }

        /** @var Tracker $tracker */
        $tracker = $this->getEnvironment()->getContainer()->get('Piwik\Tracker');

        ob_start(); // TODO: this sort of stuff seems out of place here.

        try {
            $handler  = Handler\Factory::make(); // TODO: create from DI.
            $response = $tracker->main($handler, $requestSet);

            if (!is_null($response)) {
                echo $response;
            }
        } catch (\Exception $e) {
            echo "Error:" . $e->getMessage();
            return 1;
        }

        if (ob_get_level() > 1) {
            ob_end_flush();
        }

        return 0;
    }
}