<?php

namespace Piwik\Plugins\CoreHome\ReportView;

use Piwik\API\Request;
use Piwik\Common;
use Piwik\NoAccessException;
use Piwik\Period\Range;
use Piwik\DataTable;
use Piwik\Url;
use Piwik\View;

class Sparklines extends \Piwik\Plugin\ReportView
{
    const ID = 'sparklines';

    private $apiMethod;

    public function setApiMethod($apiMethod)
    {
        $this->apiMethod = $apiMethod;

        return $this;
    }

    public function getParameters()
    {
        $parameters = parent::getParameters();
        $parameters['apiMethod'] = $this->apiMethod;

        return $parameters;
    }

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
        /** @var DataTable $dataTable */
        $view = new View('@CoreHome/sparklines');
        $sparklines = array();
        $dataTable = Request::processRequest($this->apiMethod);
        foreach ($dataTable->getRows() as $row) {
            $sparklines[] = array('label' => $row->getColumn('label'), 'image_url' => $row->getMetadata('url'));
        }

        $view->sparklines = $sparklines;

        return $view->render();
    }

    /**
     * Returns a URL to a sparkline image for a report served by the current plugin.
     *
     * The result of this URL should be used with the [sparkline()](/api-reference/Piwik/View#twig) twig function.
     *
     * The current site ID and period will be used.
     *
     * @param string $action Method name of the controller that serves the report.
     * @param array $customParameters The array of query parameter name/value pairs that
     *                                should be set in result URL.
     * @return string The generated URL.
     * @api
     */
    protected function getUrlSparkline($action, $customParameters = array())
    {
        $params = $this->getGraphParamsModified(
            array('viewDataTable' => 'sparkline',
                'action'        => $this->report->getAction(),
                'module'        => $this->report->getModule())
            + $this->report->getParameters()
            + $customParameters
        );
        // convert array values to comma separated
        foreach ($params as &$value) {
            if (is_array($value)) {
                $value = rawurlencode(implode(',', $value));
            }
        }
        $url = Url::getCurrentQueryStringWithParametersModified($params);
        return $url;
    }


    /**
     * Returns the array of new processed parameters once the parameters are applied.
     * For example: if you set range=last30 and date=2008-03-10,
     *  the date element of the returned array will be "2008-02-10,2008-03-10"
     *
     * Parameters you can set:
     * - range: last30, previous10, etc.
     * - date: YYYY-MM-DD, today, yesterday
     * - period: day, week, month, year
     *
     * @param array $paramsToSet array( 'date' => 'last50', 'viewDataTable' =>'sparkline' )
     * @throws \Piwik\NoAccessException
     * @return array
     */
    protected function getGraphParamsModified($paramsToSet = array())
    {
        if (!isset($paramsToSet['period'])) {
            $period = Common::getRequestVar('period');
        } else {
            $period = $paramsToSet['period'];
        }
        if ($period == 'range') {
            return $paramsToSet;
        }
        if (!isset($paramsToSet['range'])) {
            $range = 'last30';
        } else {
            $range = $paramsToSet['range'];
        }

        if (!isset($paramsToSet['date'])) {
            $endDate = $this->strDate;
        } else {
            $endDate = $paramsToSet['date'];
        }

        if (is_null($this->site)) {
            throw new NoAccessException("Website not initialized, check that you are logged in and/or using the correct token_auth.");
        }
        $paramDate = Range::getRelativeToEndDate($period, $range, $endDate, $this->site);

        $params = array_merge($paramsToSet, array('date' => $paramDate));
        return $params;
    }
}