// Parts taken from https://github.com/thecodercoder/frontend-boilerplate/blob/master/gulpfile.js

// Importing specific gulp API functions lets us write them below as series() instead of gulp.series()
const { src, dest, watch, series, parallel } = require('gulp');

const sass = require('gulp-sass')(require('sass')),
      postcss = require('gulp-postcss'),
      autoprefixer = require('autoprefixer'),
      uglify = require('gulp-uglify');

// File paths
const files = {
    scss: 'assets/src/scss/**/*.scss',
    js: 'assets/src/js/**/*.js'
}

// Autoprefixer
const autoprefixerSettings = {
    cascade: false
}

const uglifySettings = {
    // preserveComments: 'some',
    keep_fnames: true,
    mangle: false,
    compress: false,
    output: {
      beautify: true
    }
  }
  

// Css task
function cssTask(){
    return src(files.scss)
        .pipe(sass())
        .pipe(postcss([ autoprefixer(autoprefixerSettings) ]))
        .pipe(dest('assets/css')
    );
}

// Script task
function scriptTask(){
    return src(files.js)
        .pipe(uglify(uglifySettings))
        .pipe(dest('assets/js')
    );
  }
  

// Watch task: watch SCSS and JS files for changes
// If any change, run scss and js tasks simultaneously
function watchTask(){
    watch([files.scss, files.js],
        {interval: 1000, usePolling: true}, //Makes docker work
        series(
            parallel(cssTask, scriptTask)
        )
    );
}

// Export the default Gulp task so it can be run
// Runs the scss and js tasks simultaneously
// then runs cacheBust, then watch task
exports.default = series(
    parallel(cssTask, scriptTask),
    watchTask
);