/**
 * Main controller
 */
app.controller('GulpfileController', function($scope, $http, $routeParams, TaskService, SharedData, BaseUrl) {

  $scope.package = SharedData.load($routeParams.guid);

  $scope.scope = {};
  $scope.scope.editmode = false;

  /**
   * Toggle a task and show the task body
   */
  $scope.toggle = function(task, force) {
    if (!force && $scope.toggled === task) {
      delete $scope.toggled;
      delete $scope.scope.editmode;
    } else {
      $scope.toggled = task;
    }
  };

  $scope.addMode = function() {

    if (!$scope.predefinedTasks) {
      TaskService.getPredefinedTasks().then(function(response) {
        $scope.predefinedTasks = response.data.tasks;
      });
    }
  };

  /**
   * Select a predifined task and copy it to the gulpfile
   */
  $scope.select = function(task) {

    task = angular.copy(task);

    // Add task
    TaskService.addTask($scope.package, task)
    .then(function(response) { // New task will be returned in response.data
      SharedData.addTask(response.data);
      $scope.toggle(response.data, true); // Force toggle to open the task
      $scope.scope.editmode = true;
      $scope.showAdd = false;
    }, function(data, status, headers, config) {
      $scope.error = 'Looks like there was a network error. Please check your internet connection and try again later.';
    })
    .catch(function() {
      $scope.error = 'An error occured.';
    });

   
  };

});