module.exports = function (grunt) {

    // Project configuration.

    grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),

        uglify: {

            options: {

                banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'

            },

            build: {

                src: 'src/scripts/*.js',

                dest: 'main.js'

            }

        },

        sass: {

            dist: {

                options: {

                    style: 'compact'

                },

                files: {

                    'style.css': 'src/styles/style.scss',

                }

            }

        },

        watch: {

            scripts: {

                files: [

                  'src/scripts/*.js',

                  'src/styles/*.scss',

                ],

                tasks: ['default'],

                options: {

                    spawn: false,

                }

            }

        }

    });

    // Load the plugin that provides the "uglify" task.

    grunt.loadNpmTasks('grunt-contrib');

    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.loadNpmTasks('grunt-contrib-sass');

    grunt.registerTask('default', ['sass', 'uglify']);

};
