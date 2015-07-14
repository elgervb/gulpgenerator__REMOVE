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
    $scope.toggle(task, true); // Force toggle
    $scope.showAdd = false;

    $scope.tasks.sort(function(a, b){
      return a.name > b.name;
    });

    $scope.$apply();
  };
  

});