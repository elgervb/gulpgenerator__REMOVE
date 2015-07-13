app.service('TaskService', function($http, BaseUrl) {

  var getTasks = function() {
    return $http({
      method: 'get',
      url: BaseUrl + 'tasks'
    });
  };

  return {
    getTasks: getTasks
  }

});