
/**
 * Controller to help the user create a new gulp file. This page let's the user enter the 
 * package.json details.
 */
app.controller('NewGulpfileController', function($scope, $location, SharedData) {

  $scope.package = SharedData.package;

  /**
   * When the user entered a name for the gulpfile, then the right cached version will be loaded
   */
  $scope.$watch('package.name', function(newValue, oldValue) {
    if (newValue && newValue !== oldValue) {
      var cache = SharedData.load($scope.package.name);
      if (cache) {
        $scope.package = cache;
      }
    }
  });

  /**
   * Continue to the next page: generator where the user can add, edit or delete tasks
   */
  $scope.continue = function() {
    SharedData.store();

    $location.path('/generator/' + $scope.package.name);
  };

});