
// app.js
var app = angular.module('webapp', ['ui.router']);

app.config(function($stateProvider, $urlRouterProvider, $locationProvider) {

    $urlRouterProvider.otherwise('/login');
  
    $stateProvider

        .state('login', {
          url: '/login',
          templateUrl: 'views/login.html',
          controller: 'login'
        })
        .state('bug-report', {
          url: '/bug-report',
          templateUrl: 'views/bug-report.html',
          controller: ''
        })
        .state('search', {
          url: '/search',
          templateUrl: 'views/search.html',
          controller: ''
        })
        .state('portfolio', {
          url: '/portfolio',
          templateUrl: 'views/portfolio.html',
          controller: ''
         })
        .state('leaderboard', {
          url: '/leaderboard',
          templateUrl: 'views/leaderboard.html',
          controller: ''
    });

});


app.controller('login', function ($scope, $http) {
  
  $scope.submitLogin = function () {
    
    var login_data = {
      email: $scope.login_email,
      password: $scope.login_password
    }
    
    var postData = $.param({email: $scope.login_email, password: $scope.login_password});
    
    $http({
      method : 'POST',
      url : 'test.php',
      data: postData,
      headers : {'Content-Type': 'application/x-www-form-urlencoded'}  

    }).then(function (response) {
      $scope.val = response.data;
    });
    
  }
  
  $scope.submitSignup = function () {
    
  }
  
});
