<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Tracker;

use Piwik\Application\Environment;
use Piwik\Application\Kernel\GlobalSettingsProvider;
use Piwik\Common;
use Piwik\Plugin\Manager;
use Piwik\SettingsServer;

/**
 * TODO
 */
class TrackerEnvironment extends Environment
{
    public function __construct($definitions = array())
    {
        parent::__construct('tracker', $definitions);
    }

    public function init()
    {
        SettingsServer::setIsTrackerApiRequest(); // TODO: do this here and remove method in SettingsServer. move to doPostContainerCreatedSetup() also. should not need if no DI config file uses it (currently global.php does)

        parent::init();
    }

    protected function doPostContainerCreatedSetup()
    {
        $GLOBALS['PIWIK_TRACKER_DEBUG'] = $this->isDebugEnabled();

        try {
            /** @var Manager $manager */
            $pluginManager = $this->getContainer()->get('Piwik\Plugin\Manager');
            $pluginsTracker = $pluginManager->loadTrackerPlugins();
            Common::printDebug("Loading plugins: { " . implode(", ", $pluginsTracker) . " }");
        } catch (\Exception $e) {
            Common::printDebug("ERROR: " . $e->getMessage());
        }
    }

    private function isDebugEnabled()
    {
        /** @var GlobalSettingsProvider $settingsProvider */
        $settingsProvider = $this->getContainer()->get('Piwik\Application\Kernel\GlobalSettingsProvider');
        $trackerSection = $settingsProvider->getSection('Tracker');

        $debug = (bool) $trackerSection['debug'];
        if ($debug) {
            return true;
        }

        $debugOnDemand = (bool) $trackerSection['debug_on_demand'];
        if ($debugOnDemand) {
            return (bool) Common::getRequestVar('debug', false);
        }

        return false;
    }
}