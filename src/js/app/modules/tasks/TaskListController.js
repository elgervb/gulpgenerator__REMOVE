/**
 * Main controller
 */
app.controller('TaskListController', function($scope, $routeParams, TaskService, SharedData) {

  $scope.package = SharedData.load($routeParams.guid);

  $scope.scope = {};
  $scope.scope.editmode = false;

  /**
   * Toggle a task and show the task body
   */
  $scope.toggle = function(task) {
    if ($scope.toggled === task) {
      delete $scope.toggled;
      delete $scope.scope.editmode;
    } else {
      $scope.toggled = task;
    }
  };

  $scope.addMode = function() {

    if (!$scope.predefinedTasks) {
      TaskService.getTasks().then(function(response) {
        $scope.predefinedTasks = response.data.tasks;
      });
    }
  };

  $scope.select = function(task) {
    if (!angular.isArray($scope.tasks)) {
      $scope.tasks = [];
    }
    $scope.tasks.push(task);
    $scope.toggle(task);
    $scope.showAdd = false;
  };
  

});