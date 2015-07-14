/**
 * Declaration of the main skeleton app
 */
var app = angular.module('skeleton', ['ngRoute'])

/**
 * Configuration: state your routes and other configuration items here
 */
.config(function($routeProvider, $locationProvider) {
  
  $routeProvider
    .when('/', {
      controller: 'IndexController',
      templateUrl: '/js/app/modules/index/index.html'
    })
    .when('/gulpfile/new', {
      controller: 'NewGulpfileController',
      templateUrl: '/js/app/modules/new/new.html'
    })
    .when('/gulpfile/:guid', {
      controller: 'GulpfileController',
      templateUrl: '/js/app/modules/tasks/tasklist.html'
    });

  $locationProvider.html5Mode('true');

});
