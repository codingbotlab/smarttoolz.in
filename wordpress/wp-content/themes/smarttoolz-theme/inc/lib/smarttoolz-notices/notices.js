/**
 * Customizer controls toggles
 *
 * @package SmartToolz
 */

( function( $ ) {

	/**
	 * Helper class for the main Customizer interface.
	 *
	 * @since 1.0.0
	 * @class ASTCustomizer
	 */
	var SmartToolzNotices = {

		/**
		 * Initializes our custom logic for the Customizer.
		 *
		 * @since 1.0.0
		 * @method init
		 */
		init: function()
		{
			this._bind();
		},

		/**
		 * Binds events for the SmartToolz Portfolio.
		 *
		 * @since 1.0.0
		 * @access private
		 * @method _bind
		 */
		_bind: function()
		{
			$( document ).on('click', '.smarttoolz-notice-close', SmartToolzNotices._dismissNoticeNew );
			$( document ).on('click', '.smarttoolz-notice .notice-dismiss', SmartToolzNotices._dismissNotice );
		},

		_dismissNotice: function( event ) {
			event.preventDefault();

			var repeat_notice_after = $( this ).parents('.smarttoolz-notice').data( 'repeat-notice-after' ) || '';
			var notice_id = $( this ).parents('.smarttoolz-notice').attr( 'id' ) || '';

			SmartToolzNotices._ajax( notice_id, repeat_notice_after );
		},

		_dismissNoticeNew: function( event ) {
			event.preventDefault();

			var repeat_notice_after = $( this ).attr( 'data-repeat-notice-after' ) || '';
			var notice_id = $( this ).parents('.smarttoolz-notice').attr( 'id' ) || '';

			var $el = $( this ).parents('.smarttoolz-notice');
			$el.fadeTo( 100, 0, function() {
				$el.slideUp( 100, function() {
					$el.remove();
				});
			});

			SmartToolzNotices._ajax( notice_id, repeat_notice_after );

			var link   = $( this ).attr( 'href' ) || '';
			var target = $( this ).attr( 'target' ) || '';
			if( '' !== link && '_blank' === target ) {
				window.open(link , '_blank');
			}
		},

		_ajax: function( notice_id, repeat_notice_after ) {
			
			if( '' === notice_id ) {
				return;
			}

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action            : 'smarttoolz-notice-dismiss',
					nonce             : bsfSmartToolzNotices._notice_nonce,
					notice_id         : notice_id,
					repeat_notice_after : parseInt( repeat_notice_after, 10 ),
				},
			});

		}
	};

	$( function() {
		SmartToolzNotices.init();
	} );
} )( jQuery );