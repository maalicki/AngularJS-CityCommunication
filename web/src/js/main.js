
var busStopApp = angular.module('busStopApp', [
    'ngRoute',
    'linesServices'
]);

// configure our routes
busStopApp.config(['$routeProvider', function ($routeProvider) {
        $routeProvider

                // route for the home page
                .when('/', {
                    templateUrl: 'views/home.html',
                    controller: 'homeController'
                })

                // route for the about page
                .when('/lines', {
                    templateUrl: 'views/lines.html',
                    controller: 'linesController'
                })

                // route for the contact page
                .when('/contact', {
                    templateUrl: 'views/contact.html',
                    controller: 'contactController'
                })

                // else 404
                .otherwise("/404", {templateUrl: "partials/404.html", controller: "contactController"});
    }]);

// create the controller and inject Angular's $scope
busStopApp.controller('homeController', ['$scope', function ($scope) {
        console.log("homeController");
    }]);

busStopApp.controller('linesController', ['$scope', '$routeParams', 'getLines',
    function ($scope, $routeParams, getLines) {
        $scope.loading = true;
        $scope.lines = getLines.query({busStopId: $routeParams.busStopId});
        //$scope.loading = false;
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
