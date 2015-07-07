function() {
  return gulp.src( [ '{src}' ] )
    .pipe( gulp.dest( '{dest}' ) );
}