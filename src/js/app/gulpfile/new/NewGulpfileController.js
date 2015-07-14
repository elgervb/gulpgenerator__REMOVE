
/**
 * Controller to help the user create a new gulp file. This page let's the user enter the 
 * package.json details.
 */
app.controller('NewGulpfileController', function($scope, $http, $location, SharedData, BaseUrl) {

  $scope.package = SharedData.load();

  function handleError() {
    console.error('Failed to add gulpfile details (' + status + ')');
  }

  /**
   * Continue to the next page: generator where the user can add, edit or delete tasks
   */
  $scope.continue = function() {

    $http.post(BaseUrl + 'gulpfile', $scope.package).then(function(response, status, headers, config) {
      if (response.status === 201) { // Created
        $scope.package = response.data;
        
        SharedData.store($scope.package);

        $location.path('/gulpfile/' + $scope.package.guid);
      } else {
        handleError();
      }
    }, function(data, status, headers, config) {
      handleError();
    }).catch(function() {
      handleError();
    });

    
  };

});