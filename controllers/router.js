

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
            }
          }
          
          
        })
        .state('portfolio', {
          url: '/portfolio',
          views: {
            '': {
              templateUrl: 'views/portfolio.html',
              controller: 'trader'
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
            }
          }
        })
        .state('chart', {
          url: '/stock/:symbol',
           views: {
             '': {
               templateUrl: 'views/chart.html',
               controller: 'chart'
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
            'navbar@admin-bugs': {
              templateUrl: 'views/navbar-view/navbar-admin.html',
              controller: 'navbar'
            }
          }
    });

});
