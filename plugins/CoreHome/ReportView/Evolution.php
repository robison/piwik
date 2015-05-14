<?php

namespace Piwik\Plugins\CoreHome\ReportView;

use Piwik\Common;
use Piwik\Plugin\Report;

class Evolution extends FixedVisualization
{
    protected $visualization = \Piwik\Plugins\CoreVisualizations\Visualizations\JqplotGraph\Evolution::ID;
    private $columns;

    public function setDefaultColumns($columns)
    {
        $this->columns = $columns;
        return $this;
    }

    public function getConfig()
    {
        return array();
    }

    public function getParameters()
    {
        if (empty($columns)) {
            $columns = Common::getRequestVar('columns');
        }

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