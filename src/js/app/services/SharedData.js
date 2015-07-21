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
    version: {
        major: 0,
        minor: 0,
        patch: 1
      }
  },
  gulpfile = {},
  store = function(obj) {
    package = obj;
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
    store: store,
    load: load
  }

});
