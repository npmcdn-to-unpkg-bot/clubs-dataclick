var app = angular.module('member.controller', ['member.service']);

app.controller('MemberController', function ($scope, members, clubs, MemberService) {
    if (members.length == 0) {
        $scope.members = [];
    } else {
        $scope.members = members;
    }
    $scope.clubs = clubs;

    $scope.newMember = function () {
        $scope.error = undefined;

        MemberService.store($scope.member.name, $scope.member.club.id)
            .then(function (member) {
                $scope.members.push(member[0]);

                $scope.member = {};
            })
            .catch(function (error) {
                $scope.error = error.error_description;
            })
    };
});

app.controller('MemberShowController', function ($scope, member, clubs, MemberService, $state) {
    $scope.member = member[0];
    $scope.clubs = clubs;

    $scope.addClub = function () {
        $scope.error = undefined;

        MemberService.update($scope.member.id, 'add', '/clubs', [{id: parseInt($scope.member.club.id)}])
            .then(function (member) {
                $scope.member.clubs = member[0].clubs;

                $scope.member.club = {}
            })
            .catch(function (error) {
                $scope.error = error.error_description;
            })
    };

    $scope.deleteMember = function (member) {
        if (!confirm('Confirma a exclusão do sócio ' + member.name + '?')) {
            return;
        }

        $scope.error = undefined;

        MemberService.destroy(member.id)
            .then(function () {
                $state.go('members');
            })
            .catch(function (error) {
                $scope.error = error.error_description;
            })
    };

    $scope.deleteClub = function (club) {
        if (!confirm('Confirma a desassociação do sócio do ' + club.name + '?')) {
            return;
        }

        $scope.error = undefined;

        MemberService.update($scope.member.id, 'delete', '/clubs', [{id: club.id}])
            .then(function (member) {
                $scope.member.clubs = member[0].clubs;

                $scope.member.club = {}
            })
            .catch(function (error) {
                $scope.error = error.error_description;
            })
    };
});