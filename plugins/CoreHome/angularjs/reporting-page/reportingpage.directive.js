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

    piwikReportingPage.$inject = ['$document', 'piwik', '$filter', 'piwikApi', '$rootScope'];

    function piwikReportingPage($document, piwik, $filter, piwikApi, $rootScope){
        return {
            restrict: 'A',
            scope: {
            },
            templateUrl: 'plugins/CoreHome/angularjs/reporting-page/reportingpage.directive.html?cb=' + piwik.cacheBuster,
            compile: function (element, attrs) {

                return function (scope, element, attrs, ngModel) {
                    scope.page = {};

                    scope.fetchCategory = function () {
                        var category = piwik.broadcast.getValueFromHash('category');
                        var subcategory = piwik.broadcast.getValueFromHash('subcategory');

                        if (!category || !subcategory) {
                            // load old fashioned way
                            scope.pageContentUrl = piwik.broadcast.getHashFromUrl();
                            return;
                        }

                        piwikApi.fetch({
                            method: 'API.getReportViewMetadata',
                            category: category,
                            subcategory: subcategory
                        }).then(function (response) {
                            scope.page = response;
                        });
                    }

                    scope.getReportUrl = function (report) {
                        var params_vals = report.widget_url.substr(1).split("&");

                        // available in global scope
                        var currentHashStr = broadcast.getHashFromUrl();

                        for (var i = 0; i < params_vals.length; i++) {

                            if (currentHashStr.length != 0) {
                                currentHashStr = broadcast.updateParamValue(params_vals[i], currentHashStr);
                            }
                        }

                        //var rand = parseInt(Math.random()* 100000, 10);
                        //currentHashStr = broadcast.updateParamValue('random=' + rand, currentHashStr);

                        return '?' + currentHashStr.replace('#/%3F', '');
                    }

                    scope.fetchCategory();

                    $rootScope.$on('$locationChangeSuccess', function () {
                        scope.fetchCategory();
                    });

                };
            }
        };
    }
})();