

app.config(function($stateProvider, $urlRouterProvider, $locationProvider) {

    $urlRouterProvider.otherwise('/login');
    
    $stateProvider
  
        .state('login', {
          url: '/login',   
          views: {
            '': {
              templateUrl: 'views/login.html'
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
              templateUrl: 'views/bug-report.html'
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
              templateUrl: 'views/search.html'
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
              templateUrl: 'views/portfolio.html'
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
              controller: 'leaderboard'
            },
            'navbar@leaderboard': {
              templateUrl: 'views/navbar-view/navbar-user.html',
              controller: 'navbar'
            }
          }
          
    });

});
