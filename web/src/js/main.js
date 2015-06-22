
var busStopApp = angular.module('busStopApp', [
    'ngRoute',
    'linesServices'
]);

// configure our routes
busStopApp.config(['$routeProvider', function ($routeProvider) {
        $routeProvider
                .when('/', {
                    templateUrl: 'views/home.html',
                    controller: 'homeController'
                })
                .when('/lines', {
                    templateUrl: 'views/lines.html',
                    controller: 'linesController'
                })
                .when('/messages', {
                    templateUrl: 'views/messages.html',
                    controller: 'messagesController'
                })
                .when('/contact', {
                    templateUrl: 'views/contact.html',
                    controller: 'contactController'
                })
                .otherwise("/404", {templateUrl: "partials/404.html", controller: "contactController"});
    }]);

// create the controller and inject Angular's $scope
busStopApp.controller('homeController', ['$scope', function ($scope) {
        console.log("homeController");
    }]);

busStopApp.controller('linesController', ['$scope', '$routeParams', '$http', 'getLines',
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

busStopApp.controller('messagesController', ['$scope', '$routeParams', '$http', 'getLines',
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
                url: '../api/web/app_dev.php/getMessage',
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


busStopApp.controller('contactController', ['$scope', function ($scope) {
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
