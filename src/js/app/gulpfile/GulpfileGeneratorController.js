/**
 * Main controller
 */
app.controller('GulpfileGeneratorController', function($scope, $routeParams, TaskService, SharedData) {

  $scope.package = SharedData.load($routeParams.guid);

});