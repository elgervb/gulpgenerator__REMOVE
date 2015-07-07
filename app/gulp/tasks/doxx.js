function(){
  var gulpDoxx = require('gulp-doxx');

  gulp.src( [ '{src}' ] )
    .pipe(gulpDoxx({
    	{#title}title: {title}{/title}{#urlPrefix},
        urlPrefix: "file:///"+__dirname+{urlPrefix}{/urlPrefix}
    }))
    .pipe( gulp.dest( '{dest}' ) );

}