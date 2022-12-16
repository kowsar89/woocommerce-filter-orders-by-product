const project         = require('./package.json');;
const gulp            = require('gulp');
const wpPot           = require('gulp-wp-pot');

gulp.task('pot', function () {
	return gulp.src(['**/*.php', '!__*/**', '!src/**', '!assets/**'])
	.pipe(wpPot( {
		domain: project.name,
		includePOTCreationDate: false,
		bugReport: 'contact@kowsarhossain.com',
		team: 'KowsarHossain <contact@kowsarhossain.com>'
	} ))
	.pipe(gulp.dest('languages/'+project.name+'.pot'));
});