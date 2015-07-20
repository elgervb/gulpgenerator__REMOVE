/**
 * Takes an object with properties: major, minor, patch and transforms it into a String. 
 * If the version supplied is not an object, then the input will be returned
 */
app.filter('semverFilter', function() {
  return function(version) {
    if (typeof version === 'object') {
      return version.major + '.' + version.minor + '.' + version.patch;
    }

    return version;
  };
});