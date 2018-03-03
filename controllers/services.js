
app.service('user', function($http, $cookieStore, $state) {
  
  var user = [];
  
  this.authenticate = function (type) {
    
    if (this.get()) {
      if (this.get().type == type) return true; else return false;
    } else {
      return false;
    }
  }
  
  this.login = function (user_email, user_password) {
    
    var data = JSON.stringify({email: user_email, password: user_password});

    $http({
      method : 'POST',
      url : 'routes/authentication.php',
      data: data,
      headers : {'Content-Type': 'application/json'}  

    }).then(function (response) {
      log_event("Response from server", response.data);
      
      if (response.data.valid == "true") {
        $cookieStore.put('user', response.data);
        $state.transitionTo('portfolio');
        return true;
      } else {
        return false;
      }
    });
    
  }
  
  this.logout = function () {
    user = $cookieStore.get('user');
    
    $http({
      method : 'POST',
      url : 'routes/logout.php',
      data: user,
      headers : {'Content-Type': 'application/json'}  

    }).then(function (response) {
      log_event("Response from server", response.data);
      if (response.data === "success") {
        $cookieStore.remove('user');
        $state.transitionTo('login');
      } else {
        
      }
    });
    
  }
  
  this.add = function (user_email, user_password, user_username) {
    
    var data = JSON.stringify({email: user_email, password: user_password, username: user_username});

    $http({
      method : 'POST',
      url : 'routes/signup.php',
      data: data,
      headers : {'Content-Type': 'application/json'}  

    }).then(function (response) {
      log_event("Response from server", response.data);
      if (response.data.valid == "true") {
        $cookieStore.put('user', response.data);
        $state.transitionTo('portfolio');
        return true;
      } else {
        return false;
      }
    });
    
  }
  
  this.get = function () {
    return $cookieStore.get('user');
  }
  
}).service('stock', function($rootScope, $http){
  
  var stock_list = [];
  
  this.search = function (symbol) {
    
    $http({
      method : 'GET',
      url : 'routes/find_stocks.php?ticker=' + symbol,
      headers : {'Content-Type': 'application/json'}  
    }).then(function (response) {
      log_event("Response from server", response.data);
      stock_list = response.data;
      console.log(stock_list);
      $rootScope.$broadcast('stock_change', 'true');
    });
    
  }
  
  this.get = function () {
    return stock_list;
  }
  
  this.buy = function (symbol, buy_qty) {
    
    var data = JSON.stringify({ticker: symbol, qty: buy_qty});
    
    $http({
      method : 'POST',
      url : 'routes/buy_stock.php',
      data: data,
      headers : {'Content-Type': 'application/json'}  
    }).then(function (response) {
      log_event("Response from server", response.data);
      
    })
   
  }
  
}).service('market', function () {
  
});
