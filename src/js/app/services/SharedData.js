app.service('SharedData', function(StorageService) {

  var package = {
    settings: {
      src: './src',
      dist: './dist',
      report: './report'
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
