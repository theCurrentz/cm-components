# Chroma Images Lazy Loading
PHP, Javascript & SASS component that provides an elegant PWA style mage loading solution. Inspired by Medium, Spotify and Google Images.

### Usage
#### PHP
```
if (class_exists('Chromma_Lazy_Load_Module')) {
  add_filter( 'the_content', 'Chromma_Lazy_Load_Module::content_lazyload_filter' );
}
```
#### HTML
 ```
 <img class="llreplace" />
 ```

#### JS Build
 e.g. gulp:
```
const lazyload = '../../plugins/cm-components/components/chromma-lazy-load/assets/lazy-load.js';
gulp.task('js', function() {
    return gulp
        .src([lazyload, './js/script.js'])
        .pipe(concat('myscript.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./assets/js/'));
});
```

A base scss file is also included. Image sizing and animation options can be configured under Dashboard > Tools panel. After settings are set, the scss file will be rewritten and must be manually recompiled.
