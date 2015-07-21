/**
 * Main controller
 */
app.controller('GulpfileGeneratorController', function($scope, $routeParams, $http, TaskService, SharedData, BaseUrl) {

  $scope.package = SharedData.load($routeParams.guid);

  $scope.download = function(){
    //$http.get(BaseUrl + 'generate/' + $routeParams.guid);
    var iframe = document.createElement('iframe');
    iframe.classList.add('download-frame');
    iframe.src = BaseUrl + 'generate/' + $routeParams.guid;
    iframe.style.display = "none";
    document.body.appendChild(iframe);  
  }
});