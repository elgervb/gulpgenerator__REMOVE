/**
 * Main controller
 */
app.controller('TasksController', function($scope, TaskService) {

  TaskService.getTasks().then(function(response) {
    $scope.tasks = response.data.task;
  });


  /**
   * Toggle a task and show the task body
   */
  $scope.toggle = function(task) {
    if ($scope.toggled == task.name) {
      delete $scope.toggled;
    } else {
      $scope.toggled = task.name;
    }
    
  };

});