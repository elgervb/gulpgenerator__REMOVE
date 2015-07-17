/**
 * Main controller
 */
app.controller('GulpfileController', function($scope, $routeParams, TaskService, SharedData) {

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

  /**
   * Select a predifined task and copy it to the gulpfile
   */
  $scope.select = function(task) {
    if (!angular.isArray($scope.tasks)) {
      $scope.tasks = [];
    }

    task = angular.copy(task);


    $scope.tasks.push(task);
    $scope.toggle(task, true); // Force toggle to open the task
    $scope.scope.editmode = true;
    $scope.showAdd = false;


    // Sort the list
    $scope.tasks.sort(function(a, b) {
      return a.name > b.name;
    });
  };  

});