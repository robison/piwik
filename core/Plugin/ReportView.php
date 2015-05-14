<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugin;

use Piwik\API\Proxy;
use Exception;
use Piwik\ViewDataTable\Factory as ViewDataTableFactory;

/**
 * Defines a new widget. You can create a new widget using the console command `./console generate:widget`.
 * The generated widget will guide you through the creation of a widget.
 *
 * For an example, see {@link https://github.com/piwik/piwik/blob/master/plugins/ExamplePlugin/Widgets/MyExampleWidget.php}
 *
 * @api since Piwik 2.15
 */
class ReportView
{
    /**
     * @var Report
     */
    protected $report;
    protected $name = '';
    protected $category;
    protected $subCategory;
    protected $parameters = array();

    public function getId()
    {
        $parts = explode('\\', get_class($this));

        return end($parts);
    }

    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setSubCategory($subCategory)
    {
        $this->subCategory = $subCategory;
        return $this;
    }

    public function getSubCategory()
    {
        return $this->subCategory;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getConfig()
    {
        $config = $this->getView()->config->getProperties();
        unset($config['clientSideProperties']);
        unset($config['overridableProperties']);

        return $config;
    }

    public function getRequestConfig()
    {
        $config = $this->getView()->requestConfig;

        $requestConfig = $config->getProperties();
        unset($requestConfig['clientSideParameters']);
        unset($requestConfig['overridableProperties']);
        $requestConfig['module'] = 'API';
        $requestConfig['method'] = 'API.getProcessedReport';
        $requestConfig['apiModule'] = $config->getApiModuleToRequest();
        $requestConfig['apiAction'] = $config->getApiMethodToRequest();

        return $requestConfig;
    }

    public function setOrder()
    {
        return $this;
    }

    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    public function setReport($report)
    {
        $this->report = $report;

        return $this;
    }

    public function getReport()
    {
        return $this->report;
    }

    public function getModule()
    {
        return $this->report->getModule();
    }

    public function getAction()
    {
        return $this->report->getAction();
    }

    public function getParameters()
    {
        return array('module' => $this->getModule(), 'action' => $this->getAction()) + $this->parameters;
    }

    /**
     * Renders a report depending on the configured ViewDataTable see {@link configureView()} and
     * {@link getDefaultTypeViewDataTable()}. If you want to customize the render process or just render any custom view
     * you can overwrite this method.
     *
     * @return string
     * @throws \Exception In case the given API action does not exist yet.
     * @api
     */
    public function render()
    {
        return $this->getView()->render();
    }

    public function getView()
    {
        $apiProxy = Proxy::getInstance();

        if (!$apiProxy->isExistingApiAction($this->report->getModule(), $this->report->getAction())) {
            throw new Exception("Invalid action name '$this->action' for '$this->module' plugin.");
        }

        $apiAction = $apiProxy->buildApiActionName($this->report->getModule(), $this->report->getAction());

        $view = ViewDataTableFactory::build(null, $apiAction, $this->report->getModule() . '.' . $this->report->getAction());

        return $view;
    }


}