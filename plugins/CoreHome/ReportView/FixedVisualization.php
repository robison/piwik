<?php

namespace Piwik\Plugins\CoreHome\ReportView;

use Piwik\Plugin\Report;
use Piwik\ViewDataTable\Factory as ViewDataTableFactory;

// TODO this is actually FixedViewDataTable
class FixedVisualization extends Visualization
{
    const ID = 'fixed';

    public function setVisualization($visualization)
    {
        $this->viewDataTable = $visualization;
    }

    public function getView()
    {
        $apiAction = $this->buildApiAction();

        $module = $this->report->getModule();
        $action = $this->report->getAction();

        $view = ViewDataTableFactory::build($this->viewDataTable, $apiAction, $module . '.' . $action, true);

        return $view;
    }
}