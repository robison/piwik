<?php

namespace Piwik\Plugins\CoreHome\ReportView;

use Piwik\Common;
use Piwik\Plugin\Report;
use Piwik\Plugins\CoreVisualizations\Visualizations\JqplotGraph;

class Evolution extends FixedVisualization
{
    const ID = 'evolution';

    protected $viewDataTable = JqplotGraph\Evolution::ID;
    private $columns = array();

    public function setDefaultColumns($columns)
    {
        $this->columns = $columns;
        return $this;
    }

    public function getParameters()
    {
        $columns = Common::getRequestVar('columns', $this->columns);

        $parameters = parent::getParameters();
        $parameters['columns'] = $columns;

        return $parameters;
    }

    public function getView()
    {
        $view = parent::getView();

        // here we can change to last30, range, etc.
        $view->config->show_goals = false;

        return $view;
    }
}