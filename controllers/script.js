

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
          controller: 'bug-report'
        })
        .state('search', {
          url: '/search',
          templateUrl: 'views/search.html',
          controller: 'search'
        })
        .state('portfolio', {
          url: '/portfolio',
          templateUrl: 'views/portfolio.html',
          controller: 'portfolio'
         })
        .state('leaderboard', {
          url: '/leaderboard',
          templateUrl: 'views/leaderboard.html',
          controller: 'leaderboard'
    });

});


app.controller('login', function ($scope, $http) {
 
  
  $scope.submitLogin = function () {
    
    var form_val_email = true;
    var form_val_password = true;
    
    if (validate($scope.login_email, pattern_email)) {
      form_val_email = true;
       $scope.warning_email = "";
    } else {
      form_val_email = false;
      $scope.warning_email = "Bad Email Input";
    }
    
    if (validate($scope.login_password, pattern_password)) {
      form_val_password = true;
      $scope.warning_password = "";
    } else {
      form_val_password = false;
      $scope.warning_password = "Bad Password Input";
    }
    
    if (form_val_email && form_val_password) {
      var postData = JSON.stringify({email: $scope.login_email, password: $scope.login_password});

      $http({
        method : 'POST',
        url : 'test.php',
        data: postData,
        headers : {'Content-Type': 'application/json'}  

      }).then(function (response) {
        log_event("Response from server", response.data);
      });
    }
    
  }
  
  $scope.submitSignup = function () {
    
  }
  
}).controller('bug-report', function () {
  
}).controller('search', function () {
  
}).controller('portfolio', function () {
  
}).controller('leaderboard', function () {
  
});
