app.service('TaskService', function($http) {

  var getTasks = function() {
    return $http({
      method: 'get',
      url: 'js/app/modules/tasks/services/tasks.json'
    });
  };

  return {
    getTasks: getTasks
  }

});