<?php

namespace Piwik\Plugins\CoreHome\ReportView;

use Piwik\API\Proxy;
use Piwik\Plugin\Report;
use Exception;
use Piwik\ViewDataTable\Factory as ViewDataTableFactory;

class FixedVisualization extends DefaultView
{
    protected $visualization;

    public function setVisualization($visualization)
    {
        $this->visualization = $visualization;
    }

    public function getView()
    {
        $apiAction = $this->buildApiAction();

        $module = $this->report->getModule();
        $action = $this->report->getAction();

        $view = ViewDataTableFactory::build($this->visualization, $apiAction, $module . '.' . $action, true);

        return $view;
    }
}