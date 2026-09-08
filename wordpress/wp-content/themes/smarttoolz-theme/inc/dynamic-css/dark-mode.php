<?php
/**
 * Dark palette - Dynamic CSS
 *
 * @package smarttoolz
 * @since 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'smarttoolz_dynamic_theme_css', 'smarttoolz_dark_palette_css', 11 );

/**
 * Generate dynamic CSS for Dark palette.
 *
 * @param string $dynamic_css SmartToolz Dynamic CSS.
 * @param bool   $force       Whether to forcefully bypass palette check and return the CSS. Since 4.10.0.
 *
 * @return string Generated dynamic CSS for Dark palette.
 * @since 4.9.0
 */
function smarttoolz_dark_palette_css( $dynamic_css, $force = false ) {
	/**
	 * Filter to conditionally apply dark palette CSS.
	 *
	 * @param bool $apply_css Whether to apply dark palette CSS.
	 * @return bool
	 * @since 4.11.0
	 */
	if ( ! apply_filters( 'ast_dark_palette_css', true ) ) {
		return $dynamic_css;
	}

	if ( SmartToolz_Global_Palette::is_dark_palette() || $force ) {

		$dark_palette_common_dynamic_css = array(
			'.smarttoolz-dark-mode-enable .blockUI.blockOverlay' => array(
				'background-color' => 'var( --ast-global-color-primary, var(--ast-global-color-4) ) !important',
			),
			'.ast-header-social-wrap svg' => array(
				'fill'   => 'var(--ast-global-color-2)',
				'stroke' => 'var(--ast-global-color-2)',
			),
			' .smarttoolz-dark-mode-enable .main-header-menu .sub-menu' => array(
				'background-color' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
			),
			'.smarttoolz-dark-mode-enable .ast-header-search .ast-search-menu-icon .search-form' => array(
				'border-color' => 'var(--ast-border-color) !important',
			),
			':root'                       => array(
				'border-color' => 'var(--ast-border-color) !important',
			),
			' .smarttoolz-dark-mode-enable label, .smarttoolz-dark-mode-enable legend' => array(
				'color' => 'var(--ast-global-color-2)',
			),
			' .smarttoolz-dark-mode-enable input[type="text"]:focus, .smarttoolz-dark-mode-enable input[type="number"]:focus, .smarttoolz-dark-mode-enable input[type="email"]:focus, .smarttoolz-dark-mode-enable input[type="url"]:focus, .smarttoolz-dark-mode-enable input[type="password"]:focus, .smarttoolz-dark-mode-enable input[type="search"]:focus, .smarttoolz-dark-mode-enable input[type=reset]:focus, .smarttoolz-dark-mode-enable input[type="tel"]:focus, .smarttoolz-dark-mode-enable input[type="date"]:focus, .smarttoolz-dark-mode-enable select:focus, .smarttoolz-dark-mode-enable textarea:focus, .smarttoolz-dark-mode-enable .select2-container--default .select2-selection--single .select2-selection__rendered' => array(
				'color' => 'var(--ast-global-color-2)',
			),
			' .smarttoolz-dark-mode-enable .wp-block-search.wp-block-search__button-inside .wp-block-search__inside-wrapper' => array(
				'border'  => '1px solid var(--ast-border-color)',
				'outline' => 'none',
			),
		);

		if ( class_exists( 'WooCommerce' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					' .smarttoolz-dark-mode-enable .woocommerce-js label, .smarttoolz-dark-mode-enable .woocommerce-js legend' => array(
						'color' => 'var(--ast-global-color-3)',
					),
					' .smarttoolz-dark-mode-enable .woocommerce-js div.product .woocommerce-tabs ul.tabs li a' => array(
						'color' => 'var(--ast-global-color-3)',
					),
					'.smarttoolz-dark-mode-enable .woocommerce-error, .smarttoolz-dark-mode-enable .woocommerce-info, .smarttoolz-dark-mode-enable .woocommerce-message' => array(
						'background-color' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'color'            => 'var(--ast-global-color-3)',
					),
				)
			);
		}

		if ( defined( 'WPFORMS_VERSION' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					'.smarttoolz-dark-mode-enable .wpforms-field-container .wpforms-field-label, .smarttoolz-dark-mode-enable .wpforms-field-sublabel' => array(
						'color' => 'var(--ast-global-color-2)',
					),
					'.smarttoolz-dark-mode-enable .wpcf7 input[type=file]' => array(
						'background'   => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'border-color' => 'var(--ast-border-color)',
					),
					':root body.smarttoolz-dark-mode-enable' => array(
						'--wpforms-field-background-color' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'--wpforms-label-sublabel-color'   => 'var(--ast-global-color-2)',
						'--wpforms-label-color'            => 'var(--ast-global-color-2)',
						'--wpforms-field-text-color'       => 'var(--ast-global-color-2)',
						'--wpforms-field-border-color'     => 'var(--ast-border-color)',
					),
				)
			);
		}

		if ( defined( 'CFVSW_VER' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					'.smarttoolz-dark-mode-enable .cfvsw-swatches-option.cfvsw-label-option.cfvsw-selected-swatch, .smarttoolz-dark-mode-enable .cfvsw-swatches-option:hover' => array(
						'background' => 'var(--ast-global-color-6 )',
					),
					'.smarttoolz-dark-mode-enable .cfvsw-swatches-option' => array(
						'background' => 'var(--ast-global-color-5 )',
					),
				)
			);
		}

		if ( class_exists( 'GFForms' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					'.smarttoolz-dark-mode-enable .gform-body legend, .smarttoolz-dark-mode-enable .gform-body label, .smarttoolz-dark-mode-enable .gform-theme--framework .field_sublabel_above .gform-field-label--type-sub' => array(
						'color' => 'var(--ast-global-color-2)',
					),
					'.smarttoolz-dark-mode-enable legend, .smarttoolz-dark-mode-enable label' => array(
						'color' => 'var(--ast-global-color-2)',
					),
					' .smarttoolz-dark-mode-enable .gform_page_fields .gform-grid-col input[type=text], .smarttoolz-dark-mode-enable .gform_page_fields .gform-grid-col input[type=email], .smarttoolz-dark-mode-enable .gform_page_fields .gform-grid-col input[type=password], .smarttoolz-dark-mode-enable .gfield .ginput_container input[type=text], .smarttoolz-dark-mode-enable .gform-theme--foundation .gfield textarea, .smarttoolz-dark-mode-enable .gform-theme--foundation .gfield select, .smarttoolz-dark-mode-enable .gform-theme--foundation .gfield input.large' => array(
						'background'   => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'border-color' => 'var(--ast-border-color)',
						'color'        => 'var(--ast-global-color-2)',
					),
					'.smarttoolz-dark-mode-enable .gform_page_fields .gform-grid-col input[type=text]:focus, .smarttoolz-dark-mode-enable .gform_page_fields .gform-grid-col input[type=email]:focus, .smarttoolz-dark-mode-enable .gform_page_fields .gform-grid-col input[type=password]:focus, .smarttoolz-dark-mode-enable .gfield .ginput_container input[type=text]:focus, .smarttoolz-dark-mode-enable .gform-theme--foundation .gfield textarea:focus, .smarttoolz-dark-mode-enable .gform-theme--foundation .gfield select:focus, .smarttoolz-dark-mode-enable .gform-theme--foundation .gfield input.large:focus ' => array(
						'outline-width' => 'inherit',
					),
					' .smarttoolz-dark-mode-enable .gfield_radio .gchoice, .smarttoolz-dark-mode-enable .gform-theme--framework .gfield--type-image_choice.gfield--image-choice-appearance-card .gchoice:hover' => array(
						'--gf-ctrl-bg-color'       => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'--gf-ctrl-bg-color-hover' => 'var( --ast-global-color-primary, var(--ast-global-color-4) )',
						'--gf-ctrl-bg-color-focus' => 'var( --ast-global-color-primary, var(--ast-global-color-4) )',
					),
					' .smarttoolz-dark-mode-enable .gform-theme--framework input[type]:where(:not(.gform-text-input-reset):not([type=hidden])):where(:not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *))' => array(
						'--gf-local-bg-color'     => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'--gf-local-border-color' => 'var(--ast-border-color)',
					),
					' .smarttoolz-dark-mode-enable .gform-theme--framework input[type]:where(:not(.gform-text-input-reset):not([type=hidden])):where(:not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *)):hover' => array(
						'--gf-local-bg-color'      => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'--gf-ctrl-bg-color-focus' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
					),
					' .smarttoolz-dark-mode-enable .gform-theme--framework input[type]:where(:not(.gform-text-input-reset):not([type=hidden])):where(:not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *)):focus' => array(
						'--gf-ctrl-bg-color-focus' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
					),
					' .smarttoolz-dark-mode-enable .gform-theme--framework .gfield--type-image_choice.gfield--image-choice-appearance-card .gchoice .gform-field-label' => array(
						'--gf-local-color' => 'var(--ast-global-color-2)',
					),

				)
			);
		}

		if ( defined( 'WPCF7_VERSION' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					'.smarttoolz-dark-mode-enable legend, .smarttoolz-dark-mode-enable label' => array(
						'color' => 'var(--ast-global-color-2)',
					),
					'.smarttoolz-dark-mode-enable .wpcf7 input[type=file]' => array(
						'background'   => 'var( --ast-global-color-primary, var(--ast-global-color-4) )',
						'color'        => 'var(--ast-global-color-2)',
						'border-color' => 'var(--ast-border-color)',
					),
				)
			);
		}

		if ( function_exists( 'buddypress' ) && is_buddypress() ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					'.smarttoolz-dark-mode-enable .buddypress-wrap .bp-feedback' => array(
						'background' => 'transparent',
					),
				)
			);
		}

		if ( class_exists( 'bbpress' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					'.smarttoolz-dark-mode-enable #bbpress-forums li.bbp-header, .smarttoolz-dark-mode-enable #bbpress-forums li.bbp-footer, .smarttoolz-dark-mode-enable #bbpress-forums div.odd, .smarttoolz-dark-mode-enable #bbpress-forums ul.odd, .smarttoolz-dark-mode-enable #bbpress-forums div.bbp-forum-header, .smarttoolz-dark-mode-enable #bbpress-forums div.bbp-topic-header, .smarttoolz-dark-mode-enable #bbpress-forums div.bbp-reply-header, label, legend' => array(
						'background' => 'transparent',
						'color'      => 'var(--ast-global-color-2)',
					),
					'.smarttoolz-dark-mode-enable #bbpress-forums div.even, .smarttoolz-dark-mode-enable #bbpress-forums ul.even' => array(
						'background' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'color'      => 'var(--ast-global-color-2)',
					),
					'.smarttoolz-dark-mode-enable #bbpress-forums fieldset.bbp-form' => array(
						'border-color' => 'var(--ast-border-color)',
					),
					'.smarttoolz-dark-mode-enable #bbpress-forums .bbp-template-notice' => array(
						'background-color' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'color'            => 'var(--ast-global-color-2)',
					),
				)
			);
		}

		if ( defined( 'SRFM_VER' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					'body #srfm-single-page-container' => array(
						'--srfm-bg-color' => 'var( --ast-global-color-primary, var(--ast-global-color-4) )',
					),
				)
			);
		}

		if ( defined( 'FLUENTFORM' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					' :root body ' => array(
						'--fluentform-border-color' => 'var(--ast-border-color)',
					),
					' .smarttoolz-dark-mode-enable .frm-fluent-form .choices__inner, .smarttoolz-dark-mode-enable .frm-fluent-form .choices__list--dropdown .choices__item--selectable, .smarttoolz-dark-mode-enable .frm-fluent-form .choices__inner, .smarttoolz-dark-mode-enable .fluentform .ff-checkable-grids tbody>tr:nth-child(2n-1)>td, .smarttoolz-dark-mode-enable .fluentform .ff-checkable-grids thead>tr>th, .smarttoolz-dark-mode-enable .ff_net_table tbody tr td' => array(
						'background'   => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'border-color' => 'var(--ast-border-color)',
					),
					'.smarttoolz-dark-mode-enable .frm-fluent-form .choices__list--dropdown .choices__item--selectable.is-highlighted' => array(
						'background' => 'var( --ast-global-color-alternate-background, var(--ast-global-color-6) )',
					),
					' .smarttoolz-dark-mode-enable .ff-default .ff-el-form-control:focus' => array(
						'background' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'color'      => 'var(--ast-global-color-2)',
					),
				)
			);
		}

		if ( class_exists( 'SFWD_LMS' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					'#learndash_lesson_topics_list ul>li>span.topic_item' => array(
						'background' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
					),
					'.learndash #learndash_lesson_topics_list ul>li>span.topic_item:hover' => array(
						'background' => 'var( --ast-global-color-alternate-background, var(--ast-global-color-6) )',
					),

					'body .learndash_course_content #lessons_list>div:nth-of-type(odd)' => array(
						'background' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
					),
					'body .learndash_course_content #lessons_list>div:nth-of-type(even)' => array(
						'background' => 'var( --ast-global-color-subtle-background, var(--ast-global-color-7) )',
						'color'      => 'var(--ast-global-color-2)',
					),
					'.learndash .learndash_course_content .lessons_list .notcompleted:before' => array(
						'color' => 'var(--ast-global-color-1)',
					),
					'body #quiz_list>div:nth-of-type(odd)' => array(
						'background' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
					),
					'.smarttoolz-dark-mode-enable #learndash_lessons, .smarttoolz-dark-mode-enable #learndash_quizzes, .smarttoolz-dark-mode-enable #learndash_profile, .smarttoolz-dark-mode-enable #learndash_lesson_topics_list > div' => array(
						'background' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
					),
					'.smarttoolz-dark-mode-enable #learndash_profile' => array(
						'background' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
					),
					'.smarttoolz-dark-mode-enable .learndash-wrapper .ld-item-list .ld-item-list-item, .smarttoolz-dark-mode-enable .learndash-wrapper .ld-item-list .ld-item-list-item .ld-item-name, .smarttoolz-dark-mode-enable .learndash-wrapper .ld-item-list .ld-item-list-item .ld-item-list-item-expanded:before, .smarttoolz-dark-mode-enable .learndash-wrapper .ld-table-list a.ld-table-list-item-preview, .smarttoolz-dark-mode-enable .learndash-wrapper .ld-breadcrumbs, .smarttoolz-dark-mode-enable .learndash-wrapper .ld-table-list a.ld-table-list-item-preview, .smarttoolz-dark-mode-enable .learndash-wrapper .ld-table-list .ld-table-list-items, .smarttoolz-dark-mode-enable .learndash-wrapper .ld-table-list.ld-no-pagination, .smarttoolz-dark-mode-enable .learndash-wrapper .wpProQuiz_content .wpProQuiz_response, .smarttoolz-dark-mode-enable .learndash-wrapper .wpProQuiz_graded_points, .smarttoolz-dark-mode-enable .learndash-wrapper .wpProQuiz_points, .smarttoolz-dark-mode-enable .learndash-wrapper .ld-item-list .ld-item-list-item .ld-item-list-item-expanded .ld-progress, .smarttoolz-dark-mode-enable  .learndash-wrapper .ld-table-list .ld-table-list-footer, .smarttoolz-dark-mode-enable .learndash-wrapper .ld-table-list .ld-table-list-item .ld-table-list-title a, .smarttoolz-dark-mode-enable .learndash-wrapper .ld-table-list .ld-table-list-item-preview a, .smarttoolz-dark-mode-enable .wpProQuiz_modal_window, .smarttoolz-dark-mode-enable #wpProQuiz_user_content table.wp-list-table tbody tr.categoryTr th, .smarttoolz-dark-mode-enable #wpProQuiz_user_content table.wp-list-table tfoot tr th, .smarttoolz-dark-mode-enable#wpProQuiz_user_content .wpProQuiz_response ' => array(
						'background'   => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'border-color' => 'var(--ast-border-color)',
						'color'        => 'var(--ast-global-color-2)',
					),
					'.smarttoolz-dark-mode-enable .learndash-wrapper .ld-breadcrumbs, .smarttoolz-dark-mode-enable .learndash-wrapper .ld-lesson-status, .smarttoolz-dark-mode-enable .learndash-wrapper .ld-topic-status, .smarttoolz-dark-mode-enable .learndash-wrapper .ld-course-status.ld-course-status-enrolled ' => array(
						'background' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
					),
					' .smarttoolz-dark-mode-enable .ld-propanel-widget-filtering .toggle-section' => array(
						'background'   => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'border-color' => 'var(--ast-border-color)',
					),
					' .smarttoolz-dark-mode-enable .select2-container--ld_propanel .select2-selection--multiple' => array(
						'background'   => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'border-color' => 'var(--ast-border-color)',
					),
					' .smarttoolz-dark-mode-enable .ld-propanel-widget-filtering .section-toggle.active,  .smarttoolz-dark-mode-enable .ld-propanel-widget-reporting table' => array(
						'background' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
					),
					' .smarttoolz-dark-mode-enable .ld-propanel-widget-reporting table tr th, .smarttoolz-dark-mode-enable .ld-propanel-widget-progress-chart div.propanel-admin-row div.col-1-2 div.title, .smarttoolz-dark-mode-enable .ld-propanel-widget-progress-chart div.propanel-admin-row div.col-1-2:last-child div.title, .smarttoolz-dark-mode-enable .learndash-wrapper .ld-table-list .ld-table-list-footer' => array(
						'background' => 'var( --ast-global-color-alternate-background, var(--ast-global-color-6) )',
					),
					' .smarttoolz-dark-mode-enable .ld-propanel-widget-overview .propanel-stat .stat-label a' => array(
						'color' => 'var(--ast-global-color-2)',
					),
					' .smarttoolz-dark-mode-enable .ld-propanel-widget-filtering .filter-selection.filter-section-date>input, .smarttoolz-dark-mode-enable .ld-propanel-widget-reporting table tbody' => array(
						'border-color' => 'var(--ast-border-color)',
					),
					' .smarttoolz-dark-mode-enable .flatpickr-calendar, .smarttoolz-dark-mode-enable .flatpickr-day, .smarttoolz-dark-mode-enable .flatpickr-weekday, .smarttoolz-dark-mode-enable .flatpickr-current-month .flatpickr-monthDropdown-months' => array(
						'background' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'color'      => 'var(--ast-global-color-2)',
					),
					' .smarttoolz-dark-mode-enable .learndash-wrapper .ld-item-list .ld-item-search .ld-item-search-wrapper' => array(
						'background' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
					),
				)
			);
		}

		if ( class_exists( 'LifterLMS' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					' .smarttoolz-dark-mode-enable .llms-instructor-info .llms-instructors .llms-author, .smarttoolz-dark-mode-enable .llms-access-plan .llms-access-plan-content, .smarttoolz-dark-mode-enable .llms-access-plan .llms-access-plan-footer,  .smarttoolz-dark-mode-enable  .llms-lesson-preview section, .smarttoolz-dark-mode-enable .single-lesson.ast-separate-container .llms-lesson-preview .llms-lesson-link:hover, .smarttoolz-dark-mode-enable .llms-student-dashboard .orders-table, .smarttoolz-dark-mode-enable .llms-table tbody tr:nth-child(odd) td, .smarttoolz-dark-mode-enable .llms-table tbody tr:nth-child(odd) th, .smarttoolz-dark-mode-enable  .llms-table tfoot tr, .smarttoolz-dark-mode-enable .llms-sd-notification-center .llms-notification-list-item .llms-notification:hover, .smarttoolz-dark-mode-enable .llms-sd-notification-center, .smarttoolz-dark-mode-enable .redeem-voucher .form-row input[type=text]' => array(
						'background'   => 'var( --ast-global-color-primary, var(--ast-global-color-4) )',
						'color'        => 'var(--ast-global-color-2)',
						'border-color' => 'var(--ast-border-color)',
					),
					' .smarttoolz-dark-mode-enable body .llms-form-field input:focus, .llms-form-field input:focus-visible' => array(
						'outline' => 'inherit',
					),
					' .smarttoolz-dark-mode-enable body .llms-syllabus-wrapper .llms-lesson-preview .llms-lesson-link, .smarttoolz-dark-mode-enable .llms-lesson-preview section:hover, .smarttoolz-dark-mode-enable .llms-lesson-preview section, .smarttoolz-dark-mode-enable .llms-lesson-preview, .smarttoolz-dark-mode-enable .llms-syllabus-wrapper .llms-section-title + .llms-lesson-preview, .smarttoolz-dark-mode-enable .llms-access-plan-content .llms-access-plan-pricing, .smarttoolz-dark-mode-enable .single-lesson .llms-course-navigation .llms-lesson-preview .llms-lesson-link, .smarttoolz-dark-mode-enable .llms-student-dashboard .orders-table tbody tr:nth-child(odd) td, .smarttoolz-dark-mode-enable .llms-student-dashboard .orders-table tbody tr:nth-child(odd) th, .smarttoolz-dark-mode-enable .llms-notification, .smarttoolz-dark-mode-enable .llms-notification .llms-notification-title' => array(
						'background'   => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'color'        => 'var(--ast-global-color-2)',
						'border-color' => 'var(--ast-border-color)',
					),
					' .smarttoolz-dark-mode-enable label, .smarttoolz-dark-mode-enable  legend, .smarttoolz-dark-mode-enable .select2-container--default .select2-selection--single .select2-selection__rendered, .smarttoolz-dark-mode-enable .lifterlms .llms-checkout-wrapper .llms-notice, .smarttoolz-dark-mode-enable .llms-access-plan-description' => array(
						'color' => 'var(--ast-global-color-2)',
					),
					' .smarttoolz-dark-mode-enable .select2-container .select2-selection--single, .smarttoolz-dark-mode-enable .select2-dropdown, .smarttoolz-dark-mode-enable select, .smarttoolz-dark-mode-enable .lifterlms .llms-checkout-wrapper .llms-checkout-col.llms-col-2' => array(
						'border-color' => 'var(--ast-border-color)',
					),
					' .smarttoolz-dark-mode-enable .wpforms-container input[type=range] ' => array(
						'background' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) ) !important',
					),

					' .smarttoolz-dark-mode-enable .ast-lifterlms-container .llms-loop .llms-loop-item, .smarttoolz-dark-mode-enable .ast-lifterlms-container .llms-loop .llms-loop-item .llms-loop-item-content ' => array(
						'background' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
					),
					' .smarttoolz-dark-mode-enable .ast-lifterlms-container .llms-loop .llms-loop-item .llms-loop-item-content, .smarttoolz-dark-mode-enable .llms-loop-item-content .llms-loop-title, .smarttoolz-dark-mode-enable .llms-loop-item-content .llms-meta, .smarttoolz-dark-mode-enable .llms-loop-item-content .llms-author, .smarttoolz-dark-mode-enable .llms-loop-item-content .llms-featured-pricing ' => array(
						'background'   => 'var( --ast-global-color-primary, var(--ast-global-color-4) )',
						'border-color' => 'var(--ast-border-color)',
						'color'        => 'var(--ast-global-color-2)',
					),
					' .smarttoolz-dark-mode-enable .ast-container .llms-loop-item-content .llms-loop-title:hover, .smarttoolz-dark-mode-enable .ast-lifterlms-container .llms-loop-item-content .llms-loop-title:hover, .smarttoolz-dark-mode-enable .llms-student-dashboard .llms-loop-item-content .llms-loop-title:hover' => array(
						'color' => 'var(--ast-global-color-1)',
					),
					' .smarttoolz-dark-mode-enable .gform-theme--framework .gform-field-label:where(:not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *))' => array(
						'--gf-ctrl-label-color-primary' => 'var(--ast-global-color-2)',
					),
					' .smarttoolz-dark-mode-enable .gform-theme--framework .gfield_description:where(:not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *)), .smarttoolz-dark-mode-enable .gform-theme--framework .gfield--type-product .ginput_product_price' => array(
						'--gf-ctrl-desc-color' => 'var(--ast-global-color-2)',
					),
					' .smarttoolz-dark-mode-enable .gform-theme--framework .gfield--type-product .ginput_product_price' => array(
						'--gf-field-prod-price-color' => 'var(--ast-global-color-2)',
					),
				)
			);
		}

		// Surecart comaptibility css
		if ( defined( 'SURECART_PLUGIN_FILE' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					'.smarttoolz-dark-mode-enable .sc-pill-option__wrapper .sc-pill-option__button' => array(
						'color' => 'var(--ast-global-color-2)',
					),
					'body.smarttoolz-dark-mode-enable .sc-input-group' => array(
						'--sc-input-background-color-focus' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'color' => 'var(--ast-global-color-2)',
					),
					'body.smarttoolz-dark-mode-enable .sc-input-group.sc-quantity-selector .sc-quantity-selector__control' => array(
						'color' => 'var(--ast-global-color-2)',
					),
					'.smarttoolz-dark-mode-enable .sc-drawer' => array(
						'background-color' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) ) !important',
					),
					'.smarttoolz-dark-mode-enable .sc-pill-option__wrapper .sc-pill-option__button:hover' => array(
						'color' => 'var(--ast-global-color-1)',
					),
					'.smarttoolz-dark-mode-enable .wp-block-surecart-column' => array(
						'background' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
					),
					'.smarttoolz-dark-mode-enable .sc-input-group, .smarttoolz-dark-mode-enable .sc-input-group:hover, .smarttoolz-dark-mode-enable .sc-input-group:focus-within' => array(
						'background'   => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'border-color' => 'var(--ast-border-color)',
					),
					'.smarttoolz-dark-mode-enable .sc-input-group:focus-within' => array(
						'color' => 'white',
					),
					'.smarttoolz-dark-mode-enable .wp-block-surecart-slide-out-cart' => array(
						'border-color' => 'var(--ast-border-color)',
					),
					'.smarttoolz-dark-mode-enable .sc-product-line-item__title, .smarttoolz-dark-mode-enable .sc-product-line-item__description, .smarttoolz-dark-mode-enable .sc-product-line-item__price, .smarttoolz-dark-mode-enable .sc-coupon-form, .smarttoolz-dark-mode-enable .wp-block-surecart-slide-out-cart-header__title, .smarttoolz-dark-mode-enable .sc-product-line-item__price-description, .smarttoolz-dark-mode-enable .sc-product-line-item__price-variant, .smarttoolz-dark-mode-enable .sc-product-line-item__price-amount' => array(
						'color' => 'var(--ast-global-color-2)',
					),
					'.smarttoolz-dark-mode-enable .sc-product-line-item__price-variant, .smarttoolz-dark-mode-enable .sc-product-line-item__trial, .smarttoolz-dark-mode-enable .wp-block-surecart-product-list-price' => array(
						'color' => 'var(--ast-global-color-2)',
					),
					'.smarttoolz-dark-mode-enable .wp-block-surecart-cart-icon__icon svg' => array(
						'fill' => 'none',
					),
					'.smarttoolz-dark-mode-enable  svg'       => array(
						'--sc-alert-background-color' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'fill'                        => 'var(--ast-global-color-2)',
					),
					':root body.smarttoolz-dark-mode-enable ' => array(
						'--sc-input-label-color'           => 'var(--ast-global-color-3) ',
						'--sc-card-background-color'       => 'var( --ast-global-color-secondary, var(--ast-global-color-5) ) ',
						'--sc-input-background-color-focus' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) ) ',
						'--sc-input-background-color'      => 'var( --ast-global-color-secondary, var(--ast-global-color-5) ) ',
						'--sc-select-background-color'     => 'var( --ast-global-color-secondary, var(--ast-global-color-5) ) ',
						'--sc-input-border-color'          => 'var(--ast-border-color) ',
						'--sc-select-border-color'         => 'var(--ast-border-color) ',
						'--sc-select-border-color-focus'   => 'var(--ast-border-color) ',
						'--sc-input-border-color-focus'    => 'var(--ast-border-color) ',
						'--sc-input-control-background-color' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) ) ',
						'--sc-input-control-color'         => 'var(--ast-global-color-2) ',
						'--sc-input-color-focus'           => 'var(--ast-global-color-2) ',
						'--sc-card-border-color'           => 'var(--ast-border-color) ',
						'--sc-input-color'                 => 'var(--ast-global-color-2) ',
						'--sc-panel-background-color'      => 'var( --ast-global-color-secondary, var(--ast-global-color-5) ) ',
						'--sc-menu-item-color'             => 'var(--ast-global-color-2) ',
						'--sc-input-background-color-disabled' => 'var( --ast-global-color-alternate-background, var(--ast-global-color-6) ) ',
						'--sc-input-border-color-disabled' => 'var(--ast-border-color) ',
						'--sc-input-color-disabled'        => 'var(--ast-global-color-2)',
						'--sc-choice-background-color'     => 'var( --ast-global-color-secondary, var(--ast-global-color-5) ) ',
						'--gf-color-in-ctrl'               => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'--sc-input-background-color-hover' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) ) ',
						'--sc-color-gray-50'               => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'--sc-color-white'                 => 'var( --ast-global-color-alternate-background, var(--ast-global-color-6) )',
						'--sc-color-gray-600'              => 'var(--ast-global-color-2)',
						'--sc-color-gray-800'              => 'var(--ast-global-color-2)',
						'--sc-color-gray-900'              => 'var(--ast-global-color-2)',
						'--sc-color-gray-100'              => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
					),
					'.smarttoolz-dark-mode-enable .wp-block-surecart-column.has-background' => array(
						'--sc-input-label-color'           => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'--sc-card-background-color'       => 'var(--ast-global-color-2)',
						'--sc-input-background-color'      => 'var(--ast-global-color-2)',
						'--sc-select-background-color'     => 'var(--ast-global-color-2)',
						'--sc-input-background-color-focus' => 'var(--ast-global-color-2) ',
						'--sc-input-color'                 => 'var( --ast-global-color-secondary, var(--ast-global-color-5) )',
						'--sc-input-color-focus'           => 'var( --ast-global-color-secondary, var(--ast-global-color-5) ) ',
						'--sc-panel-background-color'      => 'var(--ast-global-color-2)',
						'--sc-menu-item-color'             => 'var( --ast-global-color-secondary, var(--ast-global-color-5) ) ',
						'--sc-input-control-background-color' => 'var(--ast-global-color-2) ',
						'--sc-input-control-color'         => 'var( --ast-global-color-secondary, var(--ast-global-color-5) ) ',
						'--sc-input-background-color-hover' => 'var(--ast-global-color-2) ',
						'--sc-input-border-color'          => 'var( --ast-global-color-secondary, var(--ast-global-color-5) ) ',
						'--sc-input-border-color-focus'    => 'var( --ast-global-color-secondary, var(--ast-global-color-5) ) ',
						'--sc-input-border-color-disabled' => 'var( --ast-global-color-secondary, var(--ast-global-color-5) ) ',
					),
				)
			);
		}

		$dynamic_css .= smarttoolz_parse_css( $dark_palette_common_dynamic_css );
	}

	return $dynamic_css;
}
