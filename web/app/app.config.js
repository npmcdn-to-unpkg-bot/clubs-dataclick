var app = angular.module('app.config', ['ui.router']);

app.constant('API_ENDPOINT', 'http://localhost:8000');

app.config(function ($stateProvider, $urlRouterProvider) {
    var clubState = {
        name: 'clubs',
        url: '/clubs',
        templateUrl: 'app/club/index.html',
        controller: 'ClubController',
        resolve: {
            clubs: function (ClubService) {
                return ClubService.index('name');
            }
        }
    };

    var memberState = {
        name: 'members',
        url: '/members',
        templateUrl: 'app/member/index.html',
        controller: 'MemberController',
        resolve: {
            members: function (MemberService) {
                return MemberService.index();
            },
            clubs: function (ClubService) {
                return ClubService.index();
            }
        }
    };

    var memberStateShow = {
        name: 'member',
        url: '/members/{id}',
        templateUrl: 'app/member/show.html',
        controller: 'MemberShowController',
        resolve: {
            member: function (MemberService, $stateParams) {
                return MemberService.show($stateParams.id);
            },
            clubs: function (ClubService) {
                return ClubService.index();
            }
        }
    };

    var reportState = {
        name: 'report',
        url: '/report',
        templateUrl: 'app/report/index.html',
        controller: 'ReportController',
        resolve: {
            clubs: function (ClubService) {
                return ClubService.index();
            }
        }
    };

    $stateProvider.state(clubState);
    $stateProvider.state(memberState);
    $stateProvider.state(memberStateShow);
    $stateProvider.state(reportState);

    $urlRouterProvider.otherwise('clubs');
});