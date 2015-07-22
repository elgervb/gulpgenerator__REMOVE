
/**
 * Controller to help the user create a new gulp file. This page let's the user enter the 
 * package.json details.
 */
app.controller('NewGulpfileController', function($scope, $http, $location, SharedData, BaseUrl) {

  $scope.package = SharedData.load();

  /**
   * Continue to the next page: generator where the user can add, edit or delete tasks
   */
  $scope.continue = function() {

    $http.post(BaseUrl + 'gulpfile', $scope.package)
    .then(function(response, status, headers, config) {
      if (response.status === 201) { // Created
        $scope.package = response.data;
        
        SharedData.store($scope.package);

        $location.path('/gulpfile/' + $scope.package.guid);
      } else {
        $scope.error = 'Failed to add gulpfile details (Network status: ' + status + ')';
      }
    }, function(data, status, headers, config) {
      $scope.error = 'Looks like there was a network error. Please check your internet connection and try again later.';
    })
    .catch(function(data, status, headers, config) {
      $scope.error = 'Failed to add gulpfile details (Network status: ' + status + ')';
    });

    
  };

});