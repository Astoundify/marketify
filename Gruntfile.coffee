module.exports = () ->

  @initConfig
    watch:
      options:
        livereload: true
      css:
        files: [
          'css/sass/*.scss'
          'css/sass/**/*.scss'
        ]
        tasks: [ 'sass', 'concat:initial', 'cssmin', 'concat:header' ]
      js:
        files: [
          'Gruntfile.*'
          'js/coffee/*.coffee',
          'js/app/*.js'
        ]
        tasks: [ 'coffee', 'uglify' ]

    sass:
      dist:
        options:
          style: 'compressed'
        files:
          'css/style.scss.css': 'css/sass/style.scss'

    concat:
      initial:
        files:
          'css/style.css': [ 'css/vendor/*.css', 'css/style.scss.css']
      header:
        files:
          'style.css': [ 'css/_theme.css', 'css/style.css' ]

    cssmin:
      dist:
        file: 'css/style.min.css': [ 'css/style.css' ]

    coffee:
      dist:
        files:
          'js/app.js': 'js/coffee/app.coffee'

    uglify:
      dist:
        options:
          sourceMap: true
        files:
          'js/vendor.min.js': [ 'js/vendor/*.js' ]
          'js/marketify.min.js': [
            'js/vendor/*.js',
            'js/app/marketify.js'
          ]

    makepot:
      theme:
        options:
          type: 'wp-theme'

    exec:
      txpull:
        cmd: 'tx pull -a --minimum-perc=75'
      txpush_s:
        cmd: 'tx push -s'

    potomo:
      dist:
        options:
          poDel: false 
        files: [
          expand: true
          cwd: 'languages'
          src: ['*.po']
          dest: 'languages'
          ext: '.mo'
          nonull: true
        ]

  @loadNpmTasks 'grunt-contrib-watch'
  @loadNpmTasks 'grunt-contrib-coffee'
  @loadNpmTasks 'grunt-contrib-uglify'
  @loadNpmTasks 'grunt-contrib-sass'
  @loadNpmTasks 'grunt-contrib-cssmin'
  @loadNpmTasks 'grunt-contrib-concat'
  @loadNpmTasks 'grunt-wp-i18n'
  @loadNpmTasks 'grunt-exec'
  @loadNpmTasks 'grunt-potomo'
  @loadNpmTasks 'grunt-newer'

  @registerTask 'default', ['watch']

  @registerTask 'getTranslations', [ 'exec:tx_pull', 'potomo' ]
  @registerTask 'pushTranslation', [ 'makepot', 'exec:tx_push' ]

  @registerTask 'build', [ 'uglify', 'coffee', 'sass', 'cssmin', 'getTranslation', 'pushTranslation' ]