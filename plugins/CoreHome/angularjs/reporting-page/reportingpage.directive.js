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
    angular.module('piwikApp').directive('piwikReportingPage', piwikReportingPage);

    piwikReportingPage.$inject = ['$document', 'piwik', '$filter', 'piwikApi', '$rootScope', '$location'];

    function piwikReportingPage($document, piwik, $filter, piwikApi, $rootScope, $location){
        return {
            restrict: 'A',
            scope: {
            },
            templateUrl: 'plugins/CoreHome/angularjs/reporting-page/reportingpage.directive.html?cb=' + piwik.cacheBuster,
            compile: function (element, attrs) {

                return function (scope, element, attrs, ngModel) {
                    scope.page = {};

                    function getReportUrl(report) {
                        var params_vals = report.widget_url.substr(1).split("&");

                        // available in global scope
                        var currentHashStr = $location.path();

                        if (currentHashStr.length != 0) {
                            for (var i = 0; i < params_vals.length; i++) {
                                currentHashStr = broadcast.updateParamValue(params_vals[i], currentHashStr);
                            }
                        }

                        return '?' + currentHashStr.substr(1);
                    }

                    scope.renderPage = function (init) {
                        scope.page = {};
                        scope.reports = [];
                        scope.pageContentUrl = '';
                        scope.evolutionReport = '';
                        scope.sparklineReport = '';

                        // all this should be done via ng routes, url depends otherwise on translated category/subcategory which is no good
                        // this might also fix related reports?!? we need to generate module/action for category/subcategory!
                        var category = piwik.broadcast.getValueFromHash('category');
                        var subcategory = piwik.broadcast.getValueFromHash('subcategory');


                        // todo also check for module & action
                        if ((!category || !subcategory) && !init) {
                            // load old fashioned way
                            scope.pageContentUrl = '?' + $location.path().substr(1);
                            return;
                        }

                        // todo we actually have this data already from reporting menu, we should use angular routes
                        // here for even faster performance
                        // could also extract it in service could solve it as well
                        piwikApi.fetch({
                            method: 'API.getReportViewMetadata',
                            category: category,
                            subcategory: subcategory
                        }).then(function (response) {
                            var reports = [];
                            angular.forEach(response.reports, function (report) {
                                report.html_url = getReportUrl(report);

                                if (report.viewDataTable === 'graphEvolution') {
                                    scope.evolutionReport = report;
                                } else if (report.viewDataTable === 'sparklines') {
                                    scope.sparklineReport = report;
                                } else {
                                    reports.push(report);
                                }
                            });

                            scope.page = response;
                            scope.reports = reports;
                        });
                    }

                    scope.renderPage(true);

                    $rootScope.$on('$locationChangeSuccess', function () {

                        scope.renderPage();
                    });

                };
            }
        };
    }
})();