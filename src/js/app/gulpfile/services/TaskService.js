app.service('TaskService', function($http, BaseUrl) {

  var addTask = function(gulpfile, task) {
    return $http.put(BaseUrl + 'tasks/' + gulpfile.guid, task)
  },
  getPredefinedTasks = function() {
    return $http({
      method: 'get',
      url: BaseUrl + 'predefinedtasks'
    });
  };

  return {
    addTask: addTask,
    getPredefinedTasks: getPredefinedTasks
  }

});