<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Tests\Framework\TestCase;

use Piwik\Application\Environment;
use Piwik\Application\Kernel\GlobalSettingsProvider;
use Piwik\Common;
use Piwik\Tests\Framework\Mock\File;

/**
 * Base class for Unit tests.
 *
 * @since 2.10.0
 */
abstract class UnitTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Environment
     */
    protected $environment;

    public function setUp()
    {
        parent::setUp();

        GlobalSettingsProvider::unsetSingletonInstance();

        $this->initEnvironment();

        File::reset();
    }

    public function tearDown()
    {
        File::reset();

        GlobalSettingsProvider::unsetSingletonInstance();

        Common::destroy($this->environment);

        parent::tearDown();
    }

    /**
     * Use this method to return custom container configuration that you want to apply for the tests.
     *
     * @return array
     */
    protected function provideContainerConfig()
    {
        return array();
    }

    protected function initEnvironment()
    {
        $this->environment = new Environment('test', $this->provideContainerConfig(), $postBootstrappedEvent = false);
        $this->environment->init();
    }
}
