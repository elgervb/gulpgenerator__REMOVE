function(){
  var gutil = require('gulp-util');
  // log project details
  gutil.log( gutil.colors.cyan("Running gulp on project "+config.name+" v"+ config.version) );
  gutil.log( gutil.colors.cyan("Author: " + config.author.name) );
  gutil.log( gutil.colors.cyan("Email : " + config.author.email) );
  gutil.log( gutil.colors.cyan("Site  : " + config.author.url) );
  // log info
  gutil.log("If you have an enhancement or encounter a bug, please report them on", gutil.colors.magenta(config.bugs.url));
}