
app.controller('NewGulpfileController', function($scope, $location, SharedData) {

  $scope.package = SharedData.package;

  $scope.$watch('package.name', function(newValue, oldValue) {
    if (newValue && newValue !== oldValue) {
      var cache = SharedData.load($scope.package.name);
      if (cache){
        $scope.package = cache;
      }
    }
  });

  $scope.continue = function() {
    SharedData.store();

    $location.path('/generator');
  };

});