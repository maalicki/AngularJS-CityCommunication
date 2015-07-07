var timerModule = angular.module('timerModule', []);

timerModule.directive('timerToDate', ['$interval', 'dateFilter', function ($interval, dateFilter) {
        return {
            restrict: 'E',
            scope: {
                deadLine: '='
            },
            template: 'za: ',
            link: function (scope, element, attrs) {
                function updateTime() {
                    element.text(dateFilter(new Date()));
                }
                element.on('$destroy', function () {
                    $interval.cancel(timeoutId);
                });

                // start the UI update process; save the timeoutId for canceling
                timeoutId = $interval(function () {
                    updateTime(); // update DOM
                }, 1000);
            }
        };
    }]);