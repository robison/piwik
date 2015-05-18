<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Report;

use Piwik\Plugin\Report;

/**
 * Singleton that manages user access to Piwik resources.
 *
 * To check whether a user has access to a resource, use one of the {@link Piwik Piwik::checkUser...}
 * methods.
 *
 * In Piwik there are four different access levels:
 *
 * - **no access**: Users with this access level cannot view the resource.
 * - **view access**: Users with this access level can view the resource, but cannot modify it.
 * - **admin access**: Users with this access level can view and modify the resource.
 * - **Super User access**: Only the Super User has this access level. It means the user can do
 *                          whatever he/she wants.
 *
 *                          Super user access is required to set some configuration options.
 *                          All other options are specific to the user or to a website.
 *
 * Access is granted per website. Uses with access for a website can view all
 * data associated with that website.
 *
 */
class ReportWidgetFactory
{
    /**
     * @var Report
     */
    private $report  = null;

    public function __construct(Report $report)
    {
        $this->setReport($report);
    }

    private function setReport($report)
    {
        $this->report = $report;
    }

    public function createWidget()
    {
        $view = new ReportWidgetConfig();
        $view->setName($this->report->getName());
        $view->setCategory($this->report->getCategory());
        $view->setSubCategory($this->report->getSubCategory());
        $view->setModule($this->report->getModule());
        $view->setAction($this->report->getAction());

        $parameters = $this->report->getParameters();
        if (!empty($parameters)) {
            $view->setParameters($parameters);
        }

        return $view;
    }

    public function createCustomWidget($module, $action)
    {
        $view = $this->createWidget();
        $view->setModule($module);
        $view->setAction($action);

        return $view;
    }
}