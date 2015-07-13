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
    StorageService.set(package.name, package);
  },
  load = function(key) {
    console.log('loading data for key ' + key);
    var obj = StorageService.get(key)
    if (obj) {
      package = obj;
      return obj;
    }
  };

  return {
    package: package,
    store: store,
    load: load
  }

});
