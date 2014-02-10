/* jshint node:true */
module.exports = function( grunt ){
	'use strict';

	grunt.initConfig({
		// setting folder templates
		dirs: {
			css: 'css',
			fonts: 'fonts',
			js: 'js'
		},

		concat: {
            css: {
                src: [
                    'css/bootstrap.css', 'css/jplayer.css', 'magnific-popup.css'
                ],
                dest: 'vendor.min.css'
            },
            js: {
                src: [
                    'jplayer.playlist.min.js', 'jquery.flexslider-min.js', 'jquery.jplayer.min.js', 'jquery.magnific-popup.min.js'
                ],
                dest: 'vendor.min.js'
            }
        },

		cssmin: {
			minify: {
				expand: true,
				cwd: '<%= dirs.css %>/',
				src: ['vendor.css', 'vendor.min.css'],
				dest: '<%= dirs.css %>/',
				ext: '.css'
			}
		},

		// Minify .js files.
		uglify: {
			options: {
				preserveComments: 'some'
			},
			js: {
				files: [{
					expand: true,
					cwd: '<%= dirs.js %>',
					src: [
						'*.js',
						'!*.min.js'
					],
					dest: '<%= dirs.js %>',
					ext: '.min.js'
				}]
			},
		},

		shell: {
			options: {
				stdout: true,
				stderr: true
			},
			generatepot: {
				command: [
					'makepot wp-theme marketify'
				].join( '&&' )
			}
		}

	});

	// Load NPM tasks to be used here
	grunt.loadNpmTasks( 'grunt-shell' );
	grunt.loadNpmTasks( 'grunt-contrib-concat' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );

	// Register tasks
	grunt.registerTask( 'default', [
		'concat',
		'cssmin',
		'uglify',
		'shell:generatepot'
	]);

};