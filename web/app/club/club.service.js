var app = angular.module('club.service', ['app.config']);

app.service('ClubService', function ($http, API_ENDPOINT, $q) {
    this.index = function (fields) {
        var deferred = $q.defer();

        $http({
            method: 'GET',
            url: API_ENDPOINT + '/clubs' + (fields != undefined ? '?fields=' + fields : '')
        }).success(function (clubs) {
            deferred.resolve(clubs);
        }).error(function (err) {
            deferred.reject(err)
        });

        return deferred.promise;
    };

    this.show = function (id) {
        var deferred = $q.defer();

        $http({
            method: 'GET',
            url: API_ENDPOINT + '/clubs/' + id
        }).success(function (club) {
            deferred.resolve(club);
        }).error(function (err) {
            deferred.reject(err)
        });

        return deferred.promise;
    };

    this.store = function (name) {
        var deferred = $q.defer();

        $http({
            method: 'POST',
            url: API_ENDPOINT + '/clubs',
            data: {
                name: name
            }
        }).success(function (club) {
            deferred.resolve(club);
        }).error(function (err) {
            deferred.reject(err)
        });

        return deferred.promise;
    };

    this.destroy = function (id) {
        var deferred = $q.defer();

        $http({
            method: 'DELETE',
            url: API_ENDPOINT + '/clubs/' + id
        }).success(function () {
            deferred.resolve();
        }).error(function (err) {
            deferred.reject(err)
        });

        return deferred.promise;
    };
});