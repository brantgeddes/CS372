

app.config(function($stateProvider, $urlRouterProvider, $locationProvider) {

    $urlRouterProvider.otherwise('/login');
    
    $stateProvider
  
        .state('login', {
          url: '/login',   
          views: {
            '': {
              templateUrl: 'views/login.html',
              controller: 'auth'
            },
            'navbar@login': {
              templateUrl: 'views/navbar-view/navbar-empty.html'
            },
            'login-form@login': {
              templateUrl: 'views/forms/login-form.html',
              controller: 'login'
            },
            'signup-form@login': {
              templateUrl: 'views/forms/signup-form.html',
              controller: 'signup'
            }
            
          }
        })
        .state('bug-report', {
          url: '/bug-report',
          views: {
            '': {
              templateUrl: 'views/bug-report.html',
              controller: 'trader'
            },
            'navbar@bug-report': {
              templateUrl: 'views/navbar-view/navbar-user.html',
              controller: 'navbar'
            },
            'bugreport-form@bug-report': {
              templateUrl: 'views/forms/bugreport-form.html',
              controller: 'bug-report'
            },
          }   
          
          
        })
        .state('search', {
          url: '/search',
          views: {
            '': {
              templateUrl: 'views/search.html',
              controller: 'trader'
            },
            'navbar@search': {
              templateUrl: 'views/navbar-view/navbar-user.html',
              controller: 'navbar'
            },
            'search-form@search': {
              templateUrl: 'views/forms/search-form.html',
              controller: 'stock-search'
            },
            'stocklist-form@search': {
              templateUrl: 'views/forms/stocklist-form.html',
              controller: 'stock-list'
            },
		  }
		})
          
        .state('user-transactions', {
		  url: '/transactions/:name',
          views: {
            '': {
              templateUrl: 'views/user-transactions.html',
              controller: 'trader'
            },
			  'navbar@user-transactions': {
              templateUrl: 'views/navbar-view/navbar-user.html',
              controller: 'navbar'
            },
            'transactions-form@user-transactions': {
              templateUrl: 'views/forms/transactions-form.html',
              controller: 'user-transactions'
            },
		  }
        })
        .state('portfolio', {
          url: '/portfolio',
          views: {
            '': {
              templateUrl: 'views/portfolio.html',
              controller: 'trader'
            },
            'user-info@portfolio': {
              templateUrl: 'views/forms/user-info.html',
              controller: 'user-info'
            },
            'user-stocks@portfolio': {
              templateUrl: 'views/forms/user-stocks.html',
              controller: 'user-stocks'
            },
            'navbar@portfolio': {
              templateUrl: 'views/navbar-view/navbar-user.html',
              controller: 'navbar'
            }
          }
         })
        .state('leaderboard', {
          url: '/leaderboard',
          views: {
            '': {
              templateUrl: 'views/leaderboard.html',
              controller: 'trader'
            },
            'navbar@leaderboard': {
              templateUrl: 'views/navbar-view/navbar-user.html',
              controller: 'navbar'
            },
            'leaderboard-form@leaderboard': {
              templateUrl: 'views/forms/leaderboard-form.html',
              controller: 'leaderboard'
            }
          }
        })
        .state('stock-info', {
          url: '/stock/:symbol/:ref',
           views: {
             '': {
               templateUrl: 'views/stock-info.html',
               controller: 'trader'
             },
             'navbar@stock-info': {
              templateUrl: 'views/navbar-view/navbar-user.html',
              controller: 'navbar'
            },
             'chart@stock-info': {
               templateUrl: 'views/forms/chart.html',
               controller: 'chart'
             },
             'stock-table@stock-info': {
               templateUrl: 'views/forms/stock-table.html',
               controller: 'stock-info'
             }
           }
        })
        .state('admin-stocks', {
          url: '/stock-list',
          views: {
            '': {
              templateUrl: 'views/admin-stocklist.html',
              controller: 'admin'
            },
            'navbar@admin-stocks': {
              templateUrl: 'views/navbar-view/navbar-admin.html',
              controller: 'navbar'
            },
            'search-form@admin-stocks': {
              templateUrl: 'views/forms/search-form.html',
              controller: 'stock-search'
            },
            'adminstocks-form@admin-stocks': {
              templateUrl: 'views/forms/adminstocks-form.html',
              controller: 'stock-list'
            }
          }
        })
        .state('admin-bugs', {
          url: '/bug-list',
          views: {
            '': {
              templateUrl: 'views/admin-buglist.html',
              controller: 'admin'
            },
            'bug-list@admin-bugs': {
              templateUrl: 'views/forms/buglist-form.html',
              controller: 'bug-list'
            },
            'navbar@admin-bugs': {
              templateUrl: 'views/navbar-view/navbar-admin.html',
              controller: 'navbar'
            }
          }
    });

});
