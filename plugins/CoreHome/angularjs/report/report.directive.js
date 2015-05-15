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
    angular.module('piwikApp').directive('piwikReport', piwikReport);

    piwikReport.$inject = ['$document', 'piwik', '$filter', '$rootScope'];

    function piwikReport($document, piwik, $filter, $rootScope){
        var currentHashStr = '';
        return {
            restrict: 'A',
            scope: {
                report: '='
            },
            link: function(scope, element, attrs) {

                var params_vals = scope.report.widget_url.substr(1).split("&");

                // available in global scope
                var currentSearchStr = window.location.search;
                var currentHashStr = broadcast.getHashFromUrl();

                for (var i = 0; i < params_vals.length; i++) {
                    // update both the current search query and hash string
                    currentSearchStr = broadcast.updateParamValue(params_vals[i], currentSearchStr);

                    if (currentHashStr.length != 0) {
                        currentHashStr = broadcast.updateParamValue(params_vals[i], currentHashStr);
                    }
                }

                // Now load the new page.
                currentHashStr =  currentHashStr.replace('#/%3F', '');
return;
                var ajaxRequest = new ajaxHelper();
                ajaxRequest.setUrl('?' + currentHashStr);
                ajaxRequest.setFormat('html');
                ajaxRequest.useCallbackInCaseOfError();
                ajaxRequest.setErrorCallback(function () {});
                ajaxRequest.setCallback(
                    function (response) {
                        scope.content = response;
                    }
                );
                ajaxRequest.send(false);
            },
            templateUrl: currentHashStr,
        };
    }
})();