/**
 * Main controller
 */
app.controller('TasksController', function($scope, TaskService) {

  TaskService.getTasks().then(function(response) {
    $scope.tasks = response.data.task;
  });

});