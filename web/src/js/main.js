
var busStopApp = angular.module('busStopApp', [
    'ngRoute',
    'linesServices',
    'ui.bootstrap'
]);

// configure our routes
busStopApp.config(['$routeProvider', function ($routeProvider) {
        $routeProvider
                .when('/', {
                    templateUrl: 'views/home.html',
                    controller: 'HomeController'
                })
                .when('/lines', {
                    templateUrl: 'views/lines.html',
                    controller: 'LinesController'
                })
                .when('/messages', {
                    templateUrl: 'views/messages.html',
                    controller: 'MessagesController'
                })
                .when('/contact', {
                    templateUrl: 'views/contact.html',
                    controller: 'ContactController'
                })
                .otherwise("/404", {templateUrl: "partials/404.html", controller: "ContactController"});
    }]);

// create the controller and inject Angular's $scope
busStopApp.controller('HomeController', ['$scope', '$http', function ($scope, $http) {
    $('[data-toggle="tooltip"]').tooltip();

    $scope.submit = function() {
        $http({
            method: "POST",
            url: '../api/web/app_dev.php/getTimeTable',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            data: $.param({
                busstop: $scope.busstop
            })
        }).success(function (data, status, header, config) {
            $scope.lines = data;
        });  
    };


    $scope.getLocation = function(val) {
      return $http.get('../api/web/app.php/getBusStopName', {
        params: {
          str: val,
          sensor: false
        }
      }).then(function(response){
        return response.data.results.map(function(item){
          return item.name;
        });
      });
    };

    function getLineTypes() {
        $http({
            method: "POST",
            url: '../api/web/app.php/getLineTypes',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            data: $.param({
                type: 'name'
            })
        }).success(function (data, status, header, config) {
            $scope.lineTypes = data;
        });  
    }
        
}]);

busStopApp.controller('LinesController', ['$scope', '$routeParams', '$http', 'getLines',
    function ($scope, $routeParams, $http, getLines) {
        $scope.loading = true;
        //$scope.lines = getLines.query({busStopId: $routeParams.busStopId});
        $http.get('../api/web/app.php/getLineTypes')
                .success(function (data, status, header, config) {
                    $scope.lines = data;
                    $scope.loading = false;
                })
                .error(function (data, status, header, config) {
                    console.log(status);
                });
    }]);

busStopApp.controller('MessagesController', ['$scope', '$routeParams', '$http', 'getLines',
    function ($scope, $routeParams, $http, getLines) {

        $scope.loading = true;
        $scope.dayLimit = '';
        
        getMessages();
        $scope.submit = function() {
          getMessages();
        };
        
        function getMessages() {
            $http({
                method: "POST",
                url: '../api/web/app.php/getMessage',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data: $.param({
                    dayLimit: $scope.dayLimit
                })
            }).success(function (data, status, header, config) {
                $scope.messages = data;
                $scope.loading = false;
            });  
        }
        

    }]);


busStopApp.controller('ContactController', ['$scope', function ($scope) {
        $scope.submit = function () {
            bootbox.alert(
                    '<br/><div class="alert alert-info" role="alert">This is <b>demo</b> app!</div>' +
                    '<b>Name:</b> ' + $scope.name +
                    '<br/><b>Email:</b> ' + $scope.email +
                    '<br/><b>Message:</b> ' + $scope.message +
                    '<br/><b>Answer:</b> ' + $scope.human + ' (' + ($scope.human == 5 ? 'OK' : 'FALSE') + ')'
                    );
        };
    }]);

busStopApp.directive('timerToDate', ['$interval', 'dateFilter', function ($interval, dateFilter) {
        return {
            restrict: 'E',
            scope: {
                deathLine: '='
            },
            link: function (scope, element, attrs) {
                var timeoutId;
                
                function updateTime() {
                    //element.text(dateFilter(new Date()));
                    var timeParts = scope.deathLine.split(':');
                    var deathLine = new Date();
                    deathLine.setHours(timeParts[0]);
                    deathLine.setMinutes(timeParts[1]);
                    deathLine.setSeconds(0);
                    var currentDate = new Date();
                    //console.log(scope.deathLine);
                    var diff = deathLine - currentDate;
                    
                    if( diff > 0 ) {
                        var totalSec = diff / 1000;
                        var hours = parseInt( totalSec / 3600 ) % 24;
                        var minutes = parseInt( totalSec / 60 ) % 60;
                        var seconds = parseInt(totalSec) % 60;

                        var result = timeParts[0] + '<sup>'+ timeParts[1]+'</sup> | '  + (hours < 10 ? "0" + hours : hours) 
                                + ":" + (minutes < 10 ? "0" + minutes : minutes) + ":" + (seconds  < 10 ? "0" + seconds : seconds);
                    } else {
                        
                        var result = '<strike>' + timeParts[0] + '<sup>'+ timeParts[1]+'</sup> '  + ' | 00:00:00</strike>';
                    }
                    
                        element.html( result );
                    
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