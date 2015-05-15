<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\CoreVisualizations\Visualizations;

use Piwik\Common;
use Piwik\DataTable;
use Piwik\FrontController;
use Piwik\Plugin\ViewDataTable;

/**
 * Reads the requested DataTable from the API and prepare data for the Sparkline view.
 *
 */
class Controller extends ViewDataTable
{
    const ID = 'controller';

    public function render()
    {
        $contorller = Common::getRequestVar('controller', null);
        $parts = explode('.', $contorller);
        $module = $parts[0];
        $action = $parts[1];

        $viewDataTable = '';
        if (!empty($_GET['viewDataTable'])) {
            $_GET['viewDataTable'] = '';
        }

        $result = FrontController::getInstance()->dispatch($module, $action);

        if (!empty($viewDataTable)) {
            $_GET['viewDataTable'] = $viewDataTable;
        }

        return $result;
    }

    /**
     * @see ViewDataTable::main()
     * @return mixed
     */
    protected function buildView()
    {
    }
}
