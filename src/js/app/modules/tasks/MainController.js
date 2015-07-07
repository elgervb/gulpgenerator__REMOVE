/**
 * Main controller
 */
app.controller('MainController', function($scope, TaskService) {

  
  TaskService.getTasks().then(function(response) {
    $scope.tasks = response.data.task;
  });

});