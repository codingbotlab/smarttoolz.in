/**
 * Customizer controls toggles
 *
 * @package SmartToolz
 */

( function( $ ) {


	/**
	 * Trigger hooks
	 */
	ASTControlTrigger = {

	    /**
	     * Trigger a hook.
	     *
	     * @since 1.0.0
	     * @method triggerHook
	     * @param {String} hook The hook to trigger.
	     * @param {Array} args An array of args to pass to the hook.
		 */
	    triggerHook: function( hook, args )
	    {
	    	$( 'body' ).trigger( 'smarttoolz-control-trigger.' + hook, args );
	    },

	    /**
	     * Add a hook.
	     *
	     * @since 1.0.0
	     * @method addHook
	     * @param {String} hook The hook to add.
	     * @param {Function} callback A function to call when the hook is triggered.
	     */
	    addHook: function( hook, callback )
	    {
	    	$( 'body' ).on( 'smarttoolz-control-trigger.' + hook, callback );
	    },

	    /**
	     * Remove a hook.
	     *
	     * @since 1.0.0
	     * @method removeHook
	     * @param {String} hook The hook to remove.
	     * @param {Function} callback The callback function to remove.
	     */
	    removeHook: function( hook, callback )
	    {
		    $( 'body' ).off( 'smarttoolz-control-trigger.' + hook, callback );
	    },
	};

	/**
	 * Helper class that contains data for showing and hiding controls.
	 *
	 * @since 1.0.0
	 * @class ASTCustomizerToggles
	 */
	ASTCustomizerToggles = {

		'smarttoolz-settings[display-site-title-responsive]' : [],

		'smarttoolz-settings[display-site-tagline-responsive]' : [],

		'smarttoolz-settings[ast-header-retina-logo]' :[],

		'custom_logo' : [],

		/**
		 * Section - Header
		 *
		 * @link  ?autofocus[section]=section-header
		 */

		/**
		 * Layout 2
		 */
		// Layout 2 > Right Section > Text / HTML
		// Layout 2 > Right Section > Search Type
		// Layout 2 > Right Section > Search Type > Search Box Type.
		'smarttoolz-settings[header-main-rt-section]' : [],


		'smarttoolz-settings[hide-custom-menu-mobile]' :[],


		/**
		 * Blog
		 */
		'smarttoolz-settings[blog-width]' :[],

		'smarttoolz-settings[blog-post-structure]' :[],

		/**
		 * Blog Single
		 */
		 'smarttoolz-settings[blog-single-post-structure]' : [],

		'smarttoolz-settings[blog-single-width]' : [],

		'smarttoolz-settings[blog-single-meta]' :[],


		/**
		 * Small Footer
		 */
		'smarttoolz-settings[footer-sml-layout]' : [],

		'smarttoolz-settings[footer-sml-section-1]' :[],

		'smarttoolz-settings[footer-sml-section-2]' :[],

		'smarttoolz-settings[footer-sml-divider]' :[],

		'smarttoolz-settings[header-main-sep]' :[],

		'smarttoolz-settings[disable-primary-nav]' :[],

		/**
		 * Footer Widgets
		 */
		'smarttoolz-settings[footer-adv]' :[],

		'smarttoolz-settings[shop-archive-width]' :[],

		'smarttoolz-settings[mobile-header-logo]' :[],

		'smarttoolz-settings[different-mobile-logo]' :[],
	};

} )( jQuery );
