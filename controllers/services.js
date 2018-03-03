
app.service('user', function($http, $cookieStore, $state) {
  
  var user = [];
  
  this.login = function (user_email, user_password) {
    
    var data = JSON.stringify({email: email, password: password});

    $http({
      method : 'POST',
      url : 'routes/authentication.php',
      data: data,
      headers : {'Content-Type': 'application/json'}  

    }).then(function (response) {
      log_event("Response from server", response.data);
      if (response.data === "success") {
        $cookieStore.put('user', JSON.stringify({email: user_email, username: ''}));
        $state.transitionTo('portfolio');
      } else {
        
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
  
  this.add = function (email, password, username) {
    
    var data = JSON.stringify({email: email, password: password, username: username});

    $http({
      method : 'POST',
      url : 'routes/signup.php',
      data: data,
      headers : {'Content-Type': 'application/json'}  

    }).then(function (response) {
      log_event("Response from server", response.data);
    });
    
  }
  
  this.get = function () {
    user = JSON.parse($cookieStore.get('user'));
    return user;
  }
  
});

app.service('stock', function($rootScope, $http){
  
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
  
});
