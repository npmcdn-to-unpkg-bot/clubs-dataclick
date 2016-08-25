var app = angular.module('club.controller', ['club.service']);

app.controller('ClubController', function ($scope, clubs, ClubService) {
    if (clubs.length == 0) {
        $scope.clubs = [];
    } else {
        $scope.clubs = clubs;
    }

    $scope.newClub = function () {
        $scope.error = undefined;

        ClubService.store($scope.club.name)
            .then(function (club) {
                $scope.clubs.push(club[0]);

                $scope.club = {};
            })
            .catch(function (error) {
                $scope.error = error.error_description;
            })
    };

    $scope.deleteClub = function (club) {
        if (!confirm('Confirma a exclusão do ' + club.name + '?\nOs sócios associados ao clube perderão a associação.')) {
            return;
        }

        $scope.error = undefined;

        ClubService.destroy(club.id)
            .then(function () {
                $scope.clubs.splice(getIdxForClub($scope.clubs, club.id), 1);
            })
            .catch(function (error) {
                $scope.error = error.error_description;
            })
    };

    var getIdxForClub = function (array, id) {
        for (var i = 0; i < array.length; i++) {
            if (array[i].id == id) {
                return i;
            }
        }

        return -1;
    };
});