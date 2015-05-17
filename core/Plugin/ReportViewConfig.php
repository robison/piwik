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
class ReportViewConfig
{
    protected $module = '';
    protected $action = '';
    protected $name = '';
    protected $category;
    protected $subCategory;
    protected $parameters = array();
    protected $viewDataTable = null;
    protected $order = 99;
    protected $forceViewDataTable = false;

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

    public function setDefaultView($viewDataTableId)
    {
        $this->viewDataTable = $viewDataTableId;
        return $this;
    }

    public function forceViewDataTable($viewDataTableId)
    {
        $this->forceViewDataTable = true;
        $this->setDefaultView($viewDataTableId);

        return $this;
    }

    public function getDefaultView()
    {
        return $this->viewDataTable;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    public function addParameters($parameters)
    {
        $this->parameters = array_merge($parameters, $this->parameters);
    }

    public function getParameters()
    {
        $defaultParams = array(
            'module' => $this->getModule(),
            'action' => $this->getAction(),
            'forceView' => (int) $this->forceViewDataTable
        );

        if ($this->viewDataTable) {
            $defaultParams['viewDataTable'] = $this->viewDataTable;
        }

        return $defaultParams + $this->parameters;
    }

}