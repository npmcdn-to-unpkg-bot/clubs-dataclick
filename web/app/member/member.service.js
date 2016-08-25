var app = angular.module('member.service', ['app.config']);

app.service('MemberService', function ($http, API_ENDPOINT, $q) {
    this.index = function () {
        var deferred = $q.defer();

        $http({
            method: 'GET',
            url: API_ENDPOINT + '/members'
        }).success(function (members) {
            deferred.resolve(members);
        }).error(function (err) {
            deferred.reject(err)
        });

        return deferred.promise;
    };

    this.show = function (id) {
        var deferred = $q.defer();

        $http({
            method: 'GET',
            url: API_ENDPOINT + '/members/' + id
        }).success(function (member) {
            deferred.resolve(member);
        }).error(function (err) {
            deferred.reject(err)
        });

        return deferred.promise;
    };

    this.store = function (name, clubId) {
        var deferred = $q.defer();

        $http({
            method: 'POST',
            url: API_ENDPOINT + '/members',
            data: {
                name: name,
                clubs: [
                    {id: clubId}
                ]
            }
        }).success(function (member) {
            deferred.resolve(member);
        }).error(function (err) {
            deferred.reject(err)
        });

        return deferred.promise;
    };

    this.destroy = function (id) {
        var deferred = $q.defer();

        $http({
            method: 'DELETE',
            url: API_ENDPOINT + '/members/' + id
        }).success(function () {
            deferred.resolve()
        }).error(function (err) {
            deferred.reject(err)
        });

        return deferred.promise;
    };

    this.update = function (id, op, path, value) {
        var deferred = $q.defer();

        $http({
            method: 'PATCH',
            url: API_ENDPOINT + '/members/' + id,
            data: {
                op: op,
                path: path,
                value: value
            }
        }).success(function (member) {
            deferred.resolve(member);
        }).error(function (err) {
            deferred.reject(err)
        });

        return deferred.promise;
    }
});