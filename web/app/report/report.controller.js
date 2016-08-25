var app = angular.module('report.controller', ['club.service']);

app.controller('ReportController', function ($scope, clubs) {
    $scope.clubs = clubs;
});