module.exports = function( grunt ) 
{
	grunt.initConfig(
	{
		clean: 
		{
			assets : [ 'assets/**' ],
			fonts  : [ 'assets/fonts/**' ],
			images : [ 'assets/images/**' ]
		},

		copy: 
		{
			images: 
			{
				cwd: 'src/images/',     // source
				dest: 'assets/images/', // destination
				src: '**',
				expand: true,
			},

			fonts: 
			{
				cwd: 'src/fonts/',     // source
				dest: 'assets/fonts/', // destination
				src: '**',
				expand: true,
			},

			vendor: 
			{
				files: 
				[
					// Note : cssmin does not allow '.' in filenames.
					{ src: ['node_modules/featherlight/src/featherlight.js'], dest: 'assets/js/featherlight.js' },
				],
			},
		},

		sass: 
		{
			dist: 
			{
				options: 
				{
					style: 'expanded',
				},

				files: 
				{
					// destination : source
					'assets/css/ui.css' : 'src/scss/ui.scss',
				},
			},
		},

		postcss: 
		{
			options: 
			{
				processors: 
				[
				 	// Add vendor prefixes
					require( 'autoprefixer' )( { browsers: 'last 2 versions' } ),
				],
			},

			dist: 
			{
				options : 
				{
					map : true, // inline sourcemaps
				},

				src: 'assets/css/*.css',
			},
		},

		cssmin: 
		{
			dist: 
			{
				files: [
				{
					cwd: 'assets/css',  // source
					dest: 'assets/css', // destination
					src: [ '*.css', '!*.min.css' ],
					ext: '.min.css',
					expand: true,
				}],
			},
    	},

		concat:
		{
			dist: 
			{
				// source
				src: 
				[
					'src/js/ui.js',
				],
				
				// destination
				dest: 'assets/js/ui.js',

				options : 
				{
					sourceMap : true,
				},
			},
		},

		uglify:
		{
			dist: 
			{
				files: [
				{
					cwd: 'assets/js',  // source
					dest: 'assets/js', // destination
					src: [ '**/*.js', '!**/*.min.js' ],
					expand: true,
					rename: function ( dst, src ) 
					{
						// Append `.min` to filename
						return dst + '/' + src.replace( '.js', '.min.js' );
					},
				}],

				options:
				{
					compress: 
					{
						drop_console: true,
					},
				},
			},
		},

		makepot: 
		{
			dist: 
			{
				options: 
				{
					domainPath: '/languages/', // Where to save the POT file.
					mainFile: 'widget-display-conditions.php',     // Main project file.
					potFilename: 'widget-display-conditions.pot',  // Name of the POT file.
					type: 'wp-plugin',          // Type of project (wp-plugin or wp-theme).
					exclude: [],               // List of files or directories to ignore.
					processPot: function( pot, options ) 
					{
						pot.headers['report-msgid-bugs-to']   = 'mentenmaarten@gmail.com';
						pot.headers['plural-forms']           = 'nplurals=2; plural=n != 1;';
						pot.headers['last-translator']        = 'Maarten Menten <mentenmaarten@gmail.com>\n';
						pot.headers['language-team']          = false;
						pot.headers['x-poedit-basepath']      = '.\n';
						pot.headers['x-poedit-language']      = 'English\n';
						pot.headers['x-poedit-country']       = 'UNITED STATES\n';
						pot.headers['x-poedit-sourcecharset'] = 'utf-8\n';
						pot.headers['x-poedit-keywordslist']  = '__;_e;_x;esc_html_e;esc_html__;esc_attr_e;esc_attr__;_ex:1,2c;_nx:4c,1,2;_nx_noop:4c,1,2;_x:1,2c;_n:1,2;_n_noop:1,2;__ngettext_noop:1,2;_c,_nc:4c,1,2;\n';
						pot.headers['x-textdomain-support']   = 'yes\n';

						return pot;
					},
				},
			},
		},

		watch: 
		{
			options: 
	        {
	        	// Reload browser when done. 
	        	// Include `<script src="//localhost:35729/livereload.js"></script>`.
				//livereload: true,
	        },

			css: 
			{
				files: [ 'src/scss/**/**.scss' ],
				tasks: [ 'sass', 'postcss', 'cssmin' ],
			},
			
			js: 
			{
		        files: [ 'src/js/**/*.js' ],
		        tasks: [ 'concat', 'copy', 'uglify' ],
	      	},

	      	images: 
			{
		        files: [ 'src/images/**' ],
		        tasks: [ 'clean:images', 'copy:images' ],
	      	},

	      	fonts: 
			{
		        files: [ 'src/fonts/**' ],
		        tasks: [ 'clean:fonts', 'copy:fonts' ],
	      	},
		},
	});

	// Load tasks
	grunt.loadNpmTasks( 'grunt-contrib-copy' );
	grunt.loadNpmTasks( 'grunt-contrib-sass' );
	grunt.loadNpmTasks( 'grunt-postcss' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-contrib-concat' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-contrib-clean' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );

	// Run `grunt assets` to built 'assets' folder.
	grunt.registerTask( 'assets', [ 'clean:assets', 'copy', 'sass', 'postcss', 'cssmin', 'concat', 'uglify', 'makepot' ] );
};
