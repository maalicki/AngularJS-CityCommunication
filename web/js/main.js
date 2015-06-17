
var busStopApp = angular.module('busStopApp', [
    'ngRoute'
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
busStopApp.controller('homeController', function ($scope) {
    console.log("homeController");
});

busStopApp.controller('linesController', function ($scope) {
    $scope.lines = {
         'Tramwaje' : [
            {lineNumber: 1},
            {lineNumber: 2},
            {lineNumber: 3},
            {lineNumber: 4},
            {lineNumber: 5},
            {lineNumber: 6},
            {lineNumber: 7},
            {lineNumber: 8},
            {lineNumber: 9},
        ],
         'Autobusy' : [
            {lineNumber: 10},
            {lineNumber: 20},
            {lineNumber: 30},
            {lineNumber: 40},
            {lineNumber: 50},
            {lineNumber: 60},
            {lineNumber: 70},
            {lineNumber: 80},
            {lineNumber: 90},
        ],
    };
});

busStopApp.controller('contactController', function ($scope) {
    $scope.submit = function () {
        bootbox.alert(
                '<br/><div class="alert alert-info" role="alert">This is <b>demo</b> app!</div>' +
                '<b>Name:</b> ' + $scope.name +
                '<br/><b>Email:</b> ' + $scope.email +
                '<br/><b>Message:</b> ' + $scope.message +
                '<br/><b>Answer:</b> ' + $scope.human + ' (' + ($scope.human == 5 ? 'OK' : 'FALSE') + ')'
                );
    };
});
