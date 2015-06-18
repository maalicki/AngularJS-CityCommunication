'use strict';

var linesServices = angular.module('linesServices', ['ngResource']);
linesServices.factory('getLines', ['$resource',
    function ($resource) {
        return $resource('../api/web/app.php/getLineTypes', {}, {
            query: {method: 'GET', params:{busStopId:'busStopId'}}
        });
    }]);