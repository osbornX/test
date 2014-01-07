/*global module:false*/
module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    bower: {
      install: {
        options: {
          targetDir: '/mnt/hgfs/e/cloud/web/wechats/assets/components/',
          layout: 'byComponent',
          verbose: true,
          bowerOptions: {}
        }
      }
    }
  });

  // These plugins provide necessary tasks.
  grunt.loadNpmTasks('grunt-bower-task');

  // Default task.
  grunt.registerTask('default', ['bower']);
};
