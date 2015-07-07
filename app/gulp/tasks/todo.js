function() {
  var todo = require('gulp-todo'),
  plumber = require('gulp-plumber');
  
  gulp.src( [{src}] )
    .pipe( plumber() )
    .pipe( todo() )
    .pipe( gulp.dest( {dest} ) ) // output todo.md as markdown
    .pipe( todo.reporter('json', {fileName: 'todo.json'} ) )
    .pipe( gulp.dest( dest ) ) // output todo.json as json
}