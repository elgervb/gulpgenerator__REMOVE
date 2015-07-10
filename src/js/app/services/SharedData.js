app.service('SharedData', function(StorageService) {

  var package = {
    settings: {
      src: './src',
      dist: './dist'
    }
    
  },
  gulpfile = {},
  store = function() {
    StorageService.set(package.name, package);
  },
  load = function(key) {
    console.log('loading data for key ' + key);
    var obj = StorageService.get(key)
    if (obj) {
      return obj;
    }
  };

  return {
    package: package,
    store: store,
    load: load
  }

});
