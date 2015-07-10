/**
 * Main controller
 */
app.controller('TaskListController', function($scope, TaskService) {

  TaskService.getTasks().then(function(response) {
    $scope.tasks = response.data.task;
  });

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

});