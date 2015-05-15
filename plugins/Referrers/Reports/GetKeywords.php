<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\Referrers\Reports;

use Piwik\Piwik;
use Piwik\Plugin\ViewDataTable;
use Piwik\Plugins\CoreVisualizations\Visualizations\Controller;
use Piwik\Plugins\CoreVisualizations\Visualizations\HtmlTable;
use Piwik\Plugins\Referrers\Columns\Keyword;
use Piwik\Tracker\Visit;
use Piwik\WidgetsList;

class GetKeywords extends Base
{
    protected function init()
    {
        parent::init();
        $this->dimension     = new Keyword();
        $this->name          = Piwik::translate('Referrers_Keywords');
        $this->documentation = Piwik::translate('Referrers_KeywordsReportDocumentation', '<br />');
        $this->actionToLoadSubTables = 'getSearchEnginesFromKeywordId';
        $this->hasGoalMetrics = true;
        $this->order = 3;
        $this->subCategory = 'Referrers_Keywords';
    }

    public function getWidgets()
    {
        return array(
            $this->createWidget(),
            $this->createEvolutionWidget(),
            $this->createFixedVisualizationWidget(Controller::ID)
                 ->setParameters(array('controller' => 'Referrers.allReferrers'))
                 ->setOrder(10)
        );
    }

    public function configureView(ViewDataTable $view)
    {
        $view->config->show_exclude_low_population = false;
        $view->config->addTranslation('label', Piwik::translate('General_ColumnKeyword'));

        $view->requestConfig->filter_limit = 25;

        if ($view->isViewDataTableId(HtmlTable::ID)) {
            $view->config->disable_subtable_when_show_goals = true;
        }
    }

}
