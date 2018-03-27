

app.controller('auth', function ($scope, $state, user) {
  
  if (user.authenticate('trader')) $state.transitionTo('portfolio');  
  if (user.authenticate('admin')) $state.transitionTo('admin-stocks');  
  
}).controller('login', function ($scope, $state, user) {
  	
  $scope.submitLogin = function () {
    
    var form_val_email = true;
    var form_val_password = true;
    
    if (validate($scope.login_email, pattern_email)) {
      form_val_email = true;
      $scope.warning_login_email = "";
    } else {
      form_val_email = false;
    }
    
    if (validate($scope.login_password, pattern_password)) {
      form_val_password = true;
      $scope.warning_login_password = "";
    } else {
      form_val_password = false;
    }
		
    if (form_val_email && form_val_password) {
			
			user.login($scope.login_email, $scope.login_password);
      if(user.get_login() == "true") {
				$scope.warning_login_email = "";
				$scope.warning_login_password = "";
	  	} else {
				$scope.warning_login_email = "Login Failed";
				$scope.warning_login_password = "Incorrect Password/Email";
			}
		} else {
			$scope.warning_login_email = "Login Failed";
			$scope.warning_login_password = "Incorrect Password/Email";
	  	
    }
    
  }
  
}).controller('signup', function ($scope, user){
  
  $scope.submitSignup = function () {
    
    var form_val_email = true;
    var form_val_password = true;
    var form_val_username = true;
    
    if (validate($scope.signup_email, pattern_email)) {
      form_val_email = true;
       $scope.warning_signup_email = "";
    } else {
      form_val_email = false;
    }
    
    if (validate($scope.signup_password, pattern_password) && $scope.signup_password == $scope.signup_duplicatepassword) {
      form_val_password = true;
      $scope.warning_signup_password = "";
    } else {
      form_val_password = false;
    }
    
    if (validate($scope.signup_username, pattern_username)) {
      form_val_username = true;
      $scope.warning_signup_username = "";
    } else {
      form_val_password = false;
    }
    
    if (form_val_email && form_val_password && form_val_username) {		
			user.add($scope.signup_email, $scope.signup_password, $scope.signup_username);
		
			if (user.get_login() == "true") {
				$scope.warning_login_email = "";
				$scope.warning_login_password = "";
			} else {
				if (user.get_error() == "email") $scope.warning_signup_email = "Email already exists";
				if (user.get_error() == "username") $scope.warning_signup_username = "Username already exists";
				$scope.warning_signup_password = "";
			}
		} else {
			$scope.warning_signup_email = "Signup Failed";
			$scope.warning_signup_username = "Invalid information entered";
			$scope.warning_signup_password = "";
		}
		
		$scope.$on('user_error', function (event, arg) {
			if (arg.error == "email") $scope.warning_signup_email = "Email already exists";
			if (arg.error == "username") $scope.warning_signup_username = "Username already exists";
		});
		
  }

}).controller('trader', function ($scope, $http, $state, user) {
  
  if (!user.authenticate('trader')) $state.transitionTo('login');
  
	var url = 'routes/authentication.php';
	$http({
		method : 'POST',
		url : url,
		headers : {'Content-Type': 'application/json'}  
	}).then(function (response) {
		$scope.email = response.data.email;
		$scope.username = response.data.username;
		$scope.balance = response.data.balance;
	});
	
}).controller('admin', function ($scope, $http, $state, user) {
  
  if (!user.authenticate('admin')) $state.transitionTo('login');
  
	$scope.import_stocks = function () {
		
		var url = 'routes/import_stocks.php';
		$http({
			method : 'GET',
			url : url,
			headers : {'Content-Type': 'application/json'}  
		}).then(function (response) {
			console.log(response.data);
		});
		
	};
	
}).controller('stock-search', function ($rootScope, $scope, stock) {
	if ($rootScope.search !== "")
	{
		$scope.stock_symbol = $rootScope.search
	}
	$scope.searchStocks = function (){
		$rootScope.search = $scope.stock_symbol;
		//stock.search($scope.stock_symbol);
		stock.search($rootScope.search);
  }

}).controller('stock-list', function ($scope, $state, $http, stock, user) {
  
	$scope.load_chart = function (current_symbol) {
    
    $state.transitionTo('stock-info', {symbol: current_symbol, ref: '1y'});
    
  }
  
	$scope.toggle_stock = function (stock) {
	
		var url = "routes/toggle.php?symbol=" + stock.symbol;
		$http({
				method : 'GET',
				url : url,
				headers : {'Content-Type': 'application/json'}  
			}).then(function (response) {
				$scope.stocks.find(function (curr) {
					return (curr.symbol == stock.symbol);
				}).enable = response.data.enable
					
		});
		
	}
	
  $scope.$on('stock_change', function () {
    
    var get_string = "";
    var url = "";
    var i = 0;
    
    $scope.stocks = stock.get();
    while ((i < 99) && $scope.stocks[i]) {
      get_string += $scope.stocks[i].symbol + ",";
      i++;
    }
    
    if (get_string) {
    	url = 'https://api.iextrading.com/1.0/stock/market/batch?symbols=' + get_string + '&types=quote';
    
			$http({
				method : 'GET',
				url : url,
				headers : {'Content-Type': 'application/json'}  
			}).then(function (response) {
				var return_stocks = $scope.stocks;

				$scope.stocks = [];

				return_stocks.forEach(function (stock) {
				if (user.authenticate('trader'))
				{
					if (response.data[stock.symbol].quote && stock.enable == 'true') {
						$scope.stocks.push({
							symbol: response.data[stock.symbol].quote.symbol,
							name: response.data[stock.symbol].quote.companyName,
							value: response.data[stock.symbol].quote.latestPrice,
							change: response.data[stock.symbol].quote.change,
							pchange: 100 * response.data[stock.symbol].quote.changePercent,
							enable: stock.enable
						});
					}
				}
				else if (user.authenticate('admin'))
				{
					if (response.data[stock.symbol].quote) {
						$scope.stocks.push({
						symbol: response.data[stock.symbol].quote.symbol,
						name: response.data[stock.symbol].quote.companyName,
						value: response.data[stock.symbol].quote.latestPrice,
						change: response.data[stock.symbol].quote.change,
						pchange: 100 * response.data[stock.symbol].quote.changePercent,
						enable: stock.enable
					});
					}
				}
				});
			});
		}
  });
  
}).controller('navbar', function ($scope, user) {
  
  $scope.logout = function () {
    user.logout();
  }
  
}).controller('bug-report', function ($scope, $http, $state) {
	
	$scope.submit_report = function (report) {
		
		var url = "routes/report.php";
		var data = JSON.stringify({report: report});
		
		$http({
			method : 'POST',
			url : url,
			data: data,
			headers : {'Content-Type': 'application/json'}  
		}).then(function (response) {
			console.log(response.data);
			$state.transitionTo('portfolio')
		});
	}
  
}).controller('chart', function ($scope, $http, $state, $stateParams, user) {
  
  $scope.symbol = $stateParams.symbol;
  $scope.ref = $stateParams.ref;
	var lineChart = null;
	
	$scope.change_ref = function (symbol, name, ref) {
		$state.transitionTo('stock-info', {symbol: symbol, ref: ref}, {
			location: true,
			inherit: true,
			relative: $state.$current,
			notify: false
		})
		$scope.draw_chart(symbol, name, ref);
	}
	
	$scope.draw_chart = function (symbol, name, ref) {
		
		if (lineChart) { 
			lineChart.destroy();
		}
		
		var url = 'https://api.iextrading.com/1.0/stock/' + symbol + '/chart/' + ref;
		$http({
			method : 'GET',
			url : url,
			headers : {'Content-Type': 'application/json'}  
		}).then(function (response) {
			var stock_high = [];
			var stock_close = [];
			var stock_low = [];
			var stock_open = [];
			var stock_date = [];
			for (var i = 0; response.data[i]; i++) {
				stock_date.push(response.data[i].label);
				stock_high.push(response.data[i].high);
				stock_close.push(response.data[i].close);
				stock_low.push(response.data[i].low);
				stock_open.push(response.data[i].open);
			}
			
			var canvas = document.getElementById("chartContainer");
			lineChart = new Chart(canvas, {
				type: 'line',
				data: {
					labels: stock_date,
					datasets: [{
						data: stock_high,
						label: "High",
						pointRadius: 1,
						pointHoverRadius: 5,
						borderColor: '#FF0000'	,
						fill: false
					},{
						data: stock_close,
						label: "Close",
						pointRadius: 1,
						pointHoverRadius: 5,
						borderColor: '#808000'	,
						fill: false,
						hidden: true
					},{
						data: stock_open,
						label: "Open",
						pointRadius: 1,
						pointHoverRadius: 5,
						borderColor: '#2471A3'	,
						fill: false,
						hidden: true
					},{
						data: stock_low,
						label: "Low",
						pointRadius: 1,
						pointHoverRadius: 5,
						borderColor: '#6C3483'	,
						fill: false,
						hidden: true
					}]
				},
				options: {
					title: {
						display: 'true',
						text: name
					},
					scales: {
						yAxes: [{
							scaleLabel: {
								display: true,
								labelString: 'Value (USD)'
							}
						}]
					},
					hover: {
						mode: 'nearest',
						intersect: false
					},
					tooltips: {
						mode: 'nearest',
						intersect: false
				}
			}
			});
			
		});
		
	}
	
	var url = 'https://api.iextrading.com/1.0/stock/' + $scope.symbol + '/quote';
	$http({
		method : 'GET',
		url : url,
		headers : {'Content-Type': 'application/json'}  
	}).then(function (response) {
		$scope.name = response.data.companyName;
		$scope.draw_chart($scope.symbol, $scope.name, $scope.ref);
	});
	
  
}).controller('stock-info', function ($scope, $state, $http, $stateParams, user) {
	
	$scope.symbol = $stateParams.symbol;
	
	var url = 'https://api.iextrading.com/1.0/stock/' + $scope.symbol + '/quote';
	
	$http({
		method : 'GET',
		url : url,
		headers : {'Content-Type': 'application/json'}  
	}).then(function (response) {
		
		$scope.stock = {
			symbol: response.data.symbol,
			name: response.data.companyName,
			value: response.data.latestPrice,
			change: response.data.change,
			pchange: 100 * response.data.changePercent
		}
		
	});
	
	$scope.transaction = function (symbol, quantity, value, type) {
		
		var url = 'routes/transaction.php';
		var data = {
			symbol: symbol,
			quantity: quantity,
			value: value,
			type: type
		}
		
		$http({
			method : 'POST',
			url : url,
			data : data,
			headers : {'Content-Type': 'application/json'}  
		}).then(function (response) {
			if (response.data.error) {
				console.log(response.data);
				$scope.stockerror=response.data.message;
				if (response.data.type == "authentication") {user.logout();}
			} else {
				$scope.stockerror="";
				$state.transitionTo('portfolio');
			}
		});
		
	}	
	
	$scope.return = function (name)
	{
		$state.transitionTo('search');
	}

}).controller('user-info', function ($scope, $http) {
	
}).controller('user-transactions', function ($state, $scope, $http, $stateParams) {
	console.log($stateParams.name);
    var url = "routes/user_transactions.php?name=" + $stateParams.name;
		$http({
				method : 'GET',
				url : url,
				headers : {'Content-Type': 'application/json'}  
			}).then(function (response) {
				
				$scope.transactions = response.data;
				console.log(response.data);	
					
		});
}).controller('leaderboard',function($state, $scope, $http, $filter) {
	
	$scope.page_load = false;
	
	$scope.load_transactions = function (name) {
		console.log(name);
		$state.transitionTo('user-transactions', {name: name});
  }
	
	var i = 0;
	$scope.stock_list = [];
	$scope.stocks = [];
	
	var url = "routes/leaderboard.php";
	$http({
		method : 'GET',
		url : url,
		headers : {'Content-Type': 'application/json'}  
	}).then(function (response) {
		
		$scope.leaderboard = response.data;
		$scope.page_load = true;
	});

	
}).controller('user-stocks', function ($scope, $state, $http) {
	
	$scope.load_chart = function (current_symbol) {
    
    $state.transitionTo('stock-info', {symbol: current_symbol, ref: '1y'});
    
  }
	
	var url = 'routes/return_stocks.php';
	$http({
		method : 'GET',
		url : url,
		headers : {'Content-Type': 'application/json'}  
	}).then(function (response) {
		$scope.stocks = response.data;
		var query_string = '';
		$scope.stocks.forEach(function (item) {
			query_string += item.symbol + ',';
		})
		
		if (query_string) {
    	url = 'https://api.iextrading.com/1.0/stock/market/batch?symbols=' + query_string + '&types=quote';
    
			$http({
				method : 'GET',
				url : url,
				headers : {'Content-Type': 'application/json'}  
			}).then(function (response) {
				var return_data = $scope.stocks;
				$scope.stocks = [];
				return_data.forEach(function (stock){
					$scope.stocks.push({
						symbol: response.data[stock.symbol].quote.symbol,
						name: response.data[stock.symbol].quote.companyName,
						quantity: stock.quantity,
						value: response.data[stock.symbol].quote.latestPrice
					});
				});
				
			});
		}
		
	});
	
}).controller('bug-list', function ($scope, $http) {
	
	var url = "routes/admin_report.php";
	$http({
		method : 'GET',
		url : url,
		headers : {'Content-Type': 'application/json'}
	}).then(function (response) {
		var reports = response.data;
		reports.forEach(function (curr, index) {
			reports[index].status = (reports[index].status == 1) ? "Solved" : "Active";
		});
		$scope.reports = reports;
		
	});
	
	$scope.mark_solved = function (report) {
		var url = "routes/admin_report.php";
		var data = {id: report.id};
		
		$http({
		method : 'POST',
		url : url,
		data: data,
		headers : {'Content-Type': 'application/json'}
		}).then(function (response) {
			$scope.reports.find(function (curr) {
				return (curr.id === report.id);
			}).status = "Solved";
		});
	}
	
}).controller('user-list', function ($scope, $http) {
	
	var url = "routes/user_list.php";
	$http({
		method: 'GET',
		url: url
	}).then(function (response) {
		$scope.users = response.data;
	});
	
	
	$scope.reset_user = function(user) {
		
		var url = "routes/user_list.php";
		var data = {
			id: user.id
		}
		
		$http({
			method: 'POST',
			url: url,
			data: data,
			headers: {'Content-Type' : 'application/json'}
		}).then(function (response) {
			$scope.users.find(function (curr) {
				return curr == user;
			}).balance = "RESET";
		});
	}
	
});




