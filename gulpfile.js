const project         = require('./package.json');;
const gulp            = require('gulp');
const wpPot           = require('gulp-wp-pot');
const clean           = require('gulp-clean');
const zip             = require('gulp-zip');

gulp.task('pot', function () {
	return gulp.src(['**/*.php', '!__*/**', '!src/**', '!assets/**'])
	.pipe(wpPot( {
		domain: project.name,
		bugReport: 'contact@kowsarhossain.com',
		team: 'KowsarHossain <contact@kowsarhossain.com>'
	} ))
	.pipe(gulp.dest('languages/'+project.name+'.pot'));
});

gulp.task('clean', function () {
	return gulp.src('__build/*.*', {read: false})
	.pipe(clean());
});

gulp.task('zip', function () {
	return gulp.src(['**', '!__*/**', '!node_modules/**', '!src/**', '!gulpfile.js', '!readme.md', '!package.json', '!package-lock.json', '!todo.txt'], { base: '..' })
	.pipe(zip(project.name+'.zip'))
	.pipe(gulp.dest('__build'))
});

gulp.task('build', gulp.series('pot','clean','zip'));