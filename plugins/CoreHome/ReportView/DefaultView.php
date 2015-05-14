<?php

namespace Piwik\Plugins\CoreHome\ReportView;

use Piwik\API\Proxy;
use Piwik\Plugin\Report;
use Exception;
use Piwik\ViewDataTable\Factory as ViewDataTableFactory;

class DefaultView extends \Piwik\Plugin\ReportView
{
    protected $visualization = null;

    /**
     * Creates a View for and then renders the single report template.
     *
     * Can be used for pages that display only one report to avoid having to create
     * a new template.
     *
     * @param string $title The report title.
     * @param string $reportHtml The report body HTML.
     * @return string|void The report contents if `$fetch` is true.
     */
    public function render()
    {
        return parent::render();
    }

    public function getView()
    {
        $apiAction = $this->buildApiAction();

        $module = $this->report->getModule();
        $action = $this->report->getAction();

        $view = ViewDataTableFactory::build($this->visualization, $apiAction, $module . '.' . $action);

        return $view;
    }

    protected function buildApiAction()
    {
        $module = $this->report->getModule();
        $action = $this->report->getAction();

        $apiProxy = Proxy::getInstance();

        if (!$apiProxy->isExistingApiAction($module, $action)) {
            throw new Exception("Invalid action name '$module' for '$action' plugin.");
        }

        return $apiProxy->buildApiActionName($module, $action);
    }
}