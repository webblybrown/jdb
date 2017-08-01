var gulp = require('gulp'),
gutil = require('gulp-util'),
browserify = require('gulp-browserify'),
concat = require('gulp-concat');
gulpif = require('gulp-if');
uglify = require('gulp-uglify');
bower = require('gulp-bower');
sass = require('gulp-sass');
imagemin = require('gulp-imagemin');
pngcrush = require('imagemin-pngcrush');

// Create gulp plugins from node install
// npm install --save-dev gulp-**
var jsSources,
sassSources,
outputDir,
sassStyle
// Create variables for enviroments
env = process.env.NODE_ENV || 'development';
if (env==='development') {
outputDir = './';
sassStyle = 'expanded';
} else {
outputDir = './';
sassStyle = 'compressed';
}
// Conditions for development and production enviroments
jsSources = ['js/*.js'];
sassSources = ['scss/style.scss'];
imageSources = ['images/**/*.*'];

// Paths to files
gulp.task('js', function() {
gulp.src(jsSources)
.pipe(concat('scripts.js'))
.pipe(browserify())
.pipe(gulpif (env === 'production', uglify()))
.pipe(gulp.dest('./'))
});
// Concat all javascript files
gulp.task('images', function() {
gulp.src(imageSources)
.pipe(gulpif (env === 'production', imagemin({
progressive: true,
svgoPlugins: [{removeViewBox: false}],
use: [pngcrush()]
})))
.pipe(gulpif (env === 'production', gulp.dest(outputDir + 'images')))
});
// Compress all images
gulp.task('css', function() {
gulp.src(sassSources)
.on('error', function(error) {
console.log('Error: ' + error.message);
})
.pipe(sass({outputStyle: sassStyle})
.on('error', sass.logError)
)
.pipe(gulp.dest(outputDir));
});
gulp.task('watch', function() {
gulp.watch(jsSources, ['js']);
gulp.watch(imageSources, ['images']);
gulp.watch('scss/**/*.*', ['css']);
});
// Watch task, looks for changes and automatically updates.
gulp.task('default', ['js', 'css', 'images', 'watch']);
// Runs all tasks