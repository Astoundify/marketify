'use strict';
module.exports = function(grunt) {

	grunt.initConfig({

		// watch for changes and trigger compass, jshint, uglify and livereload
		watch: {
			options: {
				livereload: true,
			},
			js: {
				files: '<%= jshint.all %>',
				tasks: ['jshint', 'uglify']
			},
			css: {
				files: [
					'style.css',
					'css/source/*.css',
					'css/source/vendor/*.css'
				],
				tasks: ['concat', 'cssmin']
			}
		},

		// javascript linting with jshint
		jshint: {
			options: {
				"force": true
			},
			all: [
				'Gruntfile.js',
				'js/source/**/*.js'
			]
		},

		// uglify to concat, minify, and make source maps
		uglify: {
			dist: {
				options: {
					sourceMap: false
				},
				files: {
					'js/plugins.min.js': [
						'js/source/vendor/**.js'
					],
					'js/main.min.js': [
						'js/source/marketify.js'
					],
					'js/customizer.min.js': [
						'js/source/customizer.js'
					]
				}
			}
		},

		concat: {
			dist: {
				files: {
					'css/plugins.css': ['css/source/vendor/*.css']
				}
			}
		},

		cssmin: {
			dist: {
				files: {
					'css/plugins.min.css': [ 'css/plugins.css' ],
					'css/bbpress.min.css': ['css/source/bbpress.css'],
					'css/entypo.min.css': ['css/source/entypo.css'],
					'css/editor-style.min.css': ['css/source/editor-style.css']
				}
			}
		},

		clean: {
			dist: {
				src: ['css/plugins.css']
			}
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

	grunt.loadNpmTasks( 'grunt-shell' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-contrib-concat' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-contrib-clean' );

	// register task
	grunt.registerTask('default', ['watch']);
	grunt.registerTask('build', ['jshint', 'uglify', 'concat', 'cssmin', 'clean', 'shell']);

};