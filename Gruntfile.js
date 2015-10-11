/**
 * Description
 * @method exports
 * @param {} grunt
 * @return
 */
module.exports = function (grunt) {

  var pkg = grunt.file.readJSON('package.json');
  // Project configuration.
  grunt.initConfig({
    pkg: pkg,
    uglify: {
      options: {
        mangle: true,
        //compress : false,
        //beautify : true,
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
      },
      my_target: {
        options: {
          footer: ""
        },
        files: {
        }
      }
    },
    jsbeautifier: {
      files: ["src/**/*.js", "!src/external/components/**/*.js"],
      options: pkg.jsbeautifier
    },
    watch: {
      options: {
        livereload: {
          port: 8081
        }
      },
      csshtml: {
        files: ['build/dist/**/*.css']
      },
      jsFiles: {
        files: ["src/**/*.js", 'src/**/*.html']
      },
      jsonFiles: {
        files: ["src/**/module.json", "!src/external/components/**/module.json"],
        tasks: ['bootloader:scan:skip']
      }
    },
    jshint: {
      files: ["src/**/*.js", "!src/external/components//**/*.js"],
      options: {
        globals: {
          jQuery: true,
          define: true,
          module: true,
          _importStyle_: true,
          is: true,
          when: true
        }
      }
    },
    bootloader: {
      options: {
        indexBundles: ["webmodules/bootloader", "maple/web2"],// ["project/app"],
        src: "./",
        dest: "build/dist",
        resourcesFile: "resource.json",
        livereloadUrl: "http://localhost:8081/livereload.js",
        bootServer: {
          port: 8085
        }
      }
    },
    webfont: {
      icons: {
        src: 'src/img/custom-icons/*.svg',
        dest: 'src/fonts/',
        destCss: 'src/fonts/style',
        options: {
          font: 'icons',
          stylesheet: 'scss',
          relativeFontPath: "../../src/fonts/",
          htmlDemo: false,
          hashes: true
        }
      }
    },
    'ftp-diff-deployer': {
      options: {
        host: 'SERVER_IP',
        port: 21,
        auth: {
          username: 'USER_NAME',
          password: 'USER_PASS'
        },
        diff: 'simple',
        exclude: ['/.idea','/.idea/**/*', '/.git/**/*','/**/.git/**', '/**/*.scss', '/node_modules/**/*','/node_modules']
      },
      src: {
        options: {
          src: 'src',
          dest: '/src',
          memory: './build/root/memory.src.json'
        }
      },
      dist: {
        options: {
          src: 'build',
          dest: '/build',
          memory: './build/root/memory.dist.json'
        }
      },
      root: {
        options: {
          src: 'build/root',
          dest: '/'
        }
      }
    }
  });


  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-connect');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-jsbeautifier');
  grunt.loadNpmTasks('grunt-webfont');
  grunt.loadNpmTasks('grunt-bootloader');
  grunt.loadNpmTasks('grunt-ftp-diff-deployer');
  grunt.loadNpmTasks('grunt-ftp-deploy');

  // Default task(s).
  grunt.registerTask('default', ['uglify', 'webfont']);
  grunt.registerTask('start-cdn-server', ['bootloader:server', "watch"]);
  grunt.registerTask('scan', ['bootloader:scan']);
  grunt.registerTask('bundlify', ['bootloader:bundlify']);
  grunt.registerTask('build', ['bundlify']);
  grunt.registerTask('check', ["jshint", 'jsbeautifier']);
  grunt.registerTask('deployroot', ['prepareroot','ftp-diff-deployer:root']);
  grunt.registerTask('deploy', ["build", 'ftp-diff-deployer:dist','ftp-diff-deployer:src','deployroot']);

  grunt.registerTask('prepareroot', function(){
    grunt.file.copy("./.htaccess", "./build/dist/root/.htaccess");
    grunt.file.copy("./web.config", "./build/dist/root/web.config");
  });

};