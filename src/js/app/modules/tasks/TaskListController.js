/**
 * Main controller
 */
app.controller('TaskListController', function($scope, TaskService, SharedData) {

  TaskService.getTasks().then(function(response) {
    $scope.tasks = response.data.task;
  });

  $scope.scope = {};
  $scope.scope.editmode = false;

  $scope.scope.data = SharedData;

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

});