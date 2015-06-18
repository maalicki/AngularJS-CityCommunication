module.exports = function (grunt) {
    grunt.initConfig({
        uglify: {
            dist: {
                files: {
                    'js/app.min.js': ['src/js/**/*.js']
                }
            }
        },
        less: {
          development: {
            options: {
              compress: true,
              yuicompress: true,
              optimization: 2
            },
            files: {
              "css/main.min.css": "src/less/main.less" // destination file and source file
            }
          }
        },
        watch: {
            js: {
                files: ['src/js/**/*.js'],
                tasks: ['uglify']
            },
            less: {
                files: ['src/less/**/*.less'],
                tasks: ['less']
            }
        }
    });
    
    grunt.registerTask('build', ['cssmin', 'uglify']);
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-less');
};