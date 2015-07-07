function() {
  var todo = require('gulp-todo'),
  plumber = require('gulp-plumber');
  
  gulp.src( [ '{src}' ] )
    .pipe( plumber() )
    .pipe( todo() )
    .pipe( gulp.dest( '{dest}' ) )
    .pipe( todo.reporter('json', {fileName: 'todo.json'} ) )
    .pipe( gulp.dest( '{dest}' ) ) 
}