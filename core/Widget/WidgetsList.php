<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Widget;

use Piwik\Cache as PiwikCache;
use Piwik\Piwik;
use Piwik\Plugin\Report;
use Piwik\Plugin\Widgets;

/**
 * Manages the global list of reports that can be displayed as dashboard widgets.
 *
 * Reports are added as dashboard widgets through the {@hook WidgetsList.addWidgets}
 * event. Observers for this event should call the {@link add()} method to add reports.
 *
 * @api
 * @method static \Piwik\WidgetsList getInstance()
 */
class WidgetsList
{
    /**
     * List of widgets
     *
     * @var WidgetConfig[]
     */
    private $widgets = array();

    /**
     * @var WidgetContainerConfig[]
     */
    private $container;

    /**
     * @var array
     */
    private $containerWidgets;

    public function addWidget(WidgetConfig $widget)
    {
        $this->widgets[] = $widget;
    }

    public function addContainer(WidgetContainerConfig $containerWidget)
    {
        $widgetId = $containerWidget->getId();

        $this->container[$widgetId] = $containerWidget;

        // widgets were added to this container, but the container did not exist yet.
        if (isset($this->containerWidgets[$widgetId])) {
            foreach ($this->containerWidgets[$widgetId] as $widget) {
                $containerWidget->addWidget($widget);
            }
            unset($this->containerWidgets[$widgetId]);
        }
    }

    public function getWidgets()
    {
        return $this->widgets;
    }

    public function addToContainerWidget($containerId, WidgetConfig $widget)
    {
        if (isset($this->container[$containerId])) {
            $this->container[$containerId]->addWidget($widget);
        } else {
            if (!isset($this->containerWidgets[$containerId])) {
                $this->containerWidgets[$containerId] = array();
            }

            $this->containerWidgets[$containerId][] = $widget;
        }
    }
}
