module.exports = function(config) {
  config.set({

    browsers: ['Firefox'],
    frameworks: ['jasmine'],
    files: [
      'vendor/jquery-3.1.1.min.js',
      'test/boot.js',
      'js/components/*.js',
      'test/**/*.spec.js',
      'css/esel.min.css',
      'test/loaded.js'
    ],
    // coverage reporter generates the coverage
    reporters: ['progress', 'coverage'],

    preprocessors: {
      // source files, that you wanna generate coverage for
      // do not include tests or libraries
      // (these files will be instrumented by Istanbul)

      'js/components/*.js': ['coverage']
    },

    // optionally, configure the reporter
    coverageReporter: {
      dir: './',
     reporters: [
       // reporters not supporting the `file` property
       { type: 'html', subdir: 'coverage' },
       { type: 'lcov', subdir: 'lcov' }
     ]
    }

  });
};
