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
    angular.module('piwikApp').directive('piwikWidget', piwikWidget);

    piwikWidget.$inject = ['piwik', '$location'];

    function piwikWidget(piwik, $location){
        return {
            restrict: 'A',
            scope: {
                widget: '='
            },
            templateUrl: 'plugins/CoreHome/angularjs/widget/widget.directive.html?cb=' + piwik.cacheBuster,
            compile: function (element, attrs) {

                return function (scope, element, attrs, ngModel) {

                    function getFullWidgetUrl(widget) {
                        var params_vals = widget.widget_url.substr(1).split("&");

                        // available in global scope
                        var currentHashStr = $location.path();

                        if (currentHashStr.length != 0) {
                            for (var i = 0; i < params_vals.length; i++) {
                                currentHashStr = piwik.broadcast.updateParamValue(params_vals[i], currentHashStr);
                            }
                        }

                        return '?' + currentHashStr.substr(1);
                    }

                    if (!scope.widget.isContainer) {
                        // we want to render only if it is not a container
                        scope.widget.html_url = getFullWidgetUrl(scope.widget);
                    }
                };
            }
        };
    }
})();