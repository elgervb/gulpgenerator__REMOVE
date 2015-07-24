app.service('TaskService', function($http, BaseUrl) {

  var getPredefinedTasks = function() {
    return $http({
      method: 'get',
      url: BaseUrl + 'predefinedtasks'
    });
  };

  return {
    getPredefinedTasks: getPredefinedTasks
  }

});