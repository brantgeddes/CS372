

app.controller('login', function ($scope, $http, $state, user) {
  
  $scope.submitLogin = function () {
    
    var form_val_email = true;
    var form_val_password = true;
    
    if (validate($scope.login_email, pattern_email)) {
      form_val_email = true;
       $scope.warning_login_email = "";
    } else {
      form_val_email = false;
      $scope.warning_login_email = "Bad Email Input";
    }
    
    if (validate($scope.login_password, pattern_password)) {
      form_val_password = true;
      $scope.warning_login_password = "";
    } else {
      form_val_password = false;
      $scope.warning_login_password = "Bad Password Input";
    }
    
    if (form_val_email && form_val_password) {
      user.login($scope.login_email, $scope.login_password);
    }
    
  }
  
}).controller('signup', function ($scope, $http, user){
  
  $scope.submitSignup = function () {
    
    var form_val_email = true;
    var form_val_password = true;
    var form_val_username = true;
    
    if (validate($scope.signup_email, pattern_email)) {
      form_val_email = true;
       $scope.warning_signup_email = "";
    } else {
      form_val_email = false;
      $scope.warning_signup_email = "Bad Email Input";
    }
    
    if (validate($scope.signup_password, pattern_password) && $scope.signup_password == $scope.signup_duplicatepassword) {
      form_val_password = true;
      $scope.warning_signup_password = "";
    } else {
      form_val_password = false;
      $scope.warning_signup_password = "Bad Password Input";
    }
    
    if (validate($scope.signup_username, pattern_username)) {
      form_val_username = true;
      $scope.warning_signup_username = "";
    } else {
      form_val_password = false;
      $scope.warning_signup_username = "Bad Username Input";
    }
    
    if (form_val_email && form_val_password && form_val_username) {
      user.add($scope.signup_email, $scope.signup_password, $scope.signup_username);
    }
  }

}).controller('bug-report', function () {
  
}).controller('stock-search', function ($rootScope, $scope, $http, stock) {
  
  $scope.search_flag = false;
  
  $scope.searchStocks = function (){
    stock.search($scope.stock_ticker);
    
  }

}).controller('stock-list', function ($scope, stock) {
  
  $scope.$on('stock_change', function () {
    console.log(stock.get());
    $scope.stocks = stock.get();
    $scope.search_flag = true;
  });
  
  $scope.buyStocks = function (buy_qty){
    stock.buy($scope.stocks.ticker, buy_qty)
    
  }
  
}).controller('portfolio', function () {
  
}).controller('leaderboard', function () {
  
}).controller('navbar', function ($scope, $state, user) {
  
  $scope.logout = function () {
    user.logout();
    $state.transitionTo('login');
  }
  
});
