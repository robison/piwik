/*!
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

/**
 * Example:
 * <div piwik-reporting-menu></div>
 */
(function () {
    angular.module('piwikApp').directive('piwikReportingMenu', piwikReportingMenu);

    piwikReportingMenu.$inject = ['$document', 'piwik', '$filter', 'piwikApi', '$location'];

    function piwikReportingMenu($document, piwik, $filter, piwikApi, $location){
        return {
            restrict: 'A',
            scope: {
            },
            templateUrl: 'plugins/CoreHome/angularjs/reporting-menu/reportingmenu.directive.html?cb=' + piwik.cacheBuster,
            compile: function (element, attrs) {

                return function (scope, element, attrs, ngModel) {
                    scope.menu = {};

                    scope.loadSubcategory = function (category) {

                        var idSite = broadcast.getValueFromHash('idSite');
                        var period = broadcast.getValueFromHash('period');
                        var date = broadcast.getValueFromHash('date');


                        var currentHashStr = broadcast.getHashFromUrl();

                        if (!currentHashStr.length <= 2) {
                            currentHashStr = window.location.search;
                        }

                        if (currentHashStr.length != 0 && category.category && category.subcategory) {
                            currentHashStr = broadcast.updateParamValue('category=' + category.category, currentHashStr);
                            currentHashStr = broadcast.updateParamValue('subcategory=' + category.subcategory, currentHashStr);
                        } else {
                            var params_vals = category.html_url.split("&");

                            // available in global scope
                            var currentHashStr = broadcast.getHashFromUrl();

                            for (var i = 0; i < params_vals.length; i++) {

                                if (currentHashStr.length != 0) {
                                    currentHashStr = broadcast.updateParamValue(params_vals[i], currentHashStr);
                                }
                            }
                        }

                        $location.path(currentHashStr);
                    };

                    piwikApi.bulkFetch([
                        {method: 'Dashboard.getDashboards'},
                        {method: 'API.getReportViewsMetadata'}
                    ]).then(function (response) {
                        var menu = {};
                        angular.forEach(response[0], function (dashboard, key) {
                            var category    = 'Dashboards'; // TODO use translation
                            var subcategory = dashboard.name;
                            dashboard.html_url = 'module=Dashboard&action=embeddedIndex&idDashboard=' + dashboard.id;

                            if (!menu[category]) {
                                menu[category] = [];
                            }

                            menu[category].push(dashboard);
                        });
                        angular.forEach(response[1], function (page, key) {
                            var category    = page.category;
                            var subcategory = page.subcategory;

                            if (!menu[category]) {
                                menu[category] = [];
                            }

                            menu[category].push(page);
                        });
                        scope.menu = menu;
                    });

                };
            }
        };
    }
})();