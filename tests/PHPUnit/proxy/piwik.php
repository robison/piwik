<?php
/**
 *  Proxy to normal piwik.php, but in testing mode
 *
 *  - Use the tests database to record Tracking data
 *  - Allows to overwrite the Visitor IP, and Server datetime
 *
 */

use Piwik\DataTable\Manager;
use Piwik\Option;
use Piwik\Plugins\UserCountry\LocationProvider\GeoIp;
use Piwik\Site;
use Piwik\Tracker;

require realpath(dirname(__FILE__)) . "/includes.php";

class TestTrackerTestEnvironment extends Tracker\TrackerEnvironment
{
    protected function doPostContainerCreatedSetup()
    {
        Tracker::setTestEnvironment();
        Manager::getInstance()->deleteAll();
        Option::clearCache();
        Site::clearCache();

        parent::doPostContainerCreatedSetup();
    }
}

class TestTrackerApplication extends Tracker\TrackerApplication
{
    public function __construct($definitions = array())
    {
        parent::__construct(new TestTrackerTestEnvironment(), $definitions);
    }
}

// Wrapping the request inside ob_start() calls to ensure that the Test
// calling us waits for the full request to process before unblocking
ob_start();

$returnCode = 0;
try {
    Piwik_TestingEnvironment::addHooks();

    GeoIp::$geoIPDatabaseDir = 'tests/lib/geoip-files';

    $trackerApp = new TestTrackerApplication();
    $returnCode = $trackerApp->track();
} catch (Exception $ex) {
    echo "Unexpected error during tracking: " . $ex->getMessage() . "\n" . $ex->getTraceAsString() . "\n";
}

if (ob_get_level() > 1) {
    ob_end_flush();
}

exit($returnCode);