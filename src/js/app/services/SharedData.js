app.service('SharedData', function(StorageService) {

  /**
   * Default settings for the package.json
   */
  var package = {
    settings: {
      src: './src',
      dest: './dist',
      report: './report'
    },
    tasks: [],
    version: {
        major: 0,
        minor: 0,
        patch: 1
      }
  },
  gulpfile = {},
  addTask = function(task) {
    package.tasks.push(task);
    // Sort tasks
    package.tasks.sort(function(a, b) {
      return a.name > b.name;
    });
    
    store(package);

    return package;
  },
  store = function(obj) {
    package = obj;
    console.assert(package.guid, 'Gulpfile does not have a GUID yet...');
    StorageService.set(package.guid, package);
  },
  load = function(guid) {
    if (package.guid === guid) {
      return package;
    }
    console.log('loading data for guid ' + guid);
    var obj = StorageService.get(guid)
    if (obj) {
      package = obj;
      return obj;
    }
  };

  return {
    addTask: addTask,
    store: store,
    load: load
  }

});
