<?php
/**
 * Footer Layout 4
 *
 * @package SmartToolz
 * @since   SmartToolz 1.0.12
 */

/**
 * Hide advanced footer markup if:
 *
 * - User is not logged in. [AND]
 * - All widgets are not active.
 */
if ( ! is_user_logged_in() ) {
	if (
		! is_active_sidebar( 'advanced-footer-widget-1' ) &&
		! is_active_sidebar( 'advanced-footer-widget-2' ) &&
		! is_active_sidebar( 'advanced-footer-widget-3' ) &&
		! is_active_sidebar( 'advanced-footer-widget-4' )
	) {
		return;
	}
}

$smarttoolz_footer_classes   = array();
$smarttoolz_footer_classes[] = 'footer-adv';
$smarttoolz_footer_classes[] = 'footer-adv-layout-4';
$smarttoolz_footer_classes   = implode( ' ', $smarttoolz_footer_classes );
?>

<div class="<?php echo esc_attr( $smarttoolz_footer_classes ); ?>">
	<div class="footer-adv-overlay">
		<div class="ast-container">
			<div class="ast-row">
				<div class="<?php echo wp_kses_post( smarttoolz_attr( 'ast-layout-4-grid' ) ); ?> footer-adv-widget footer-adv-widget-1" <?php echo wp_kses_post( apply_filters( 'smarttoolz_sidebar_data_attrs', '', 'advanced-footer-widget-1' ) ); ?>>
					<?php smarttoolz_get_footer_widget( 'advanced-footer-widget-1' ); ?>
				</div>
				<div class="<?php echo wp_kses_post( smarttoolz_attr( 'ast-layout-4-grid' ) ); ?> footer-adv-widget footer-adv-widget-2" <?php echo wp_kses_post( apply_filters( 'smarttoolz_sidebar_data_attrs', '', 'advanced-footer-widget-2' ) ); ?>>
					<?php smarttoolz_get_footer_widget( 'advanced-footer-widget-2' ); ?>
				</div>
				<div class="<?php echo wp_kses_post( smarttoolz_attr( 'ast-layout-4-grid' ) ); ?> footer-adv-widget footer-adv-widget-3" <?php echo wp_kses_post( apply_filters( 'smarttoolz_sidebar_data_attrs', '', 'advanced-footer-widget-3' ) ); ?>>
					<?php smarttoolz_get_footer_widget( 'advanced-footer-widget-3' ); ?>
				</div>
				<div class="<?php echo wp_kses_post( smarttoolz_attr( 'ast-layout-4-grid' ) ); ?> footer-adv-widget footer-adv-widget-4" <?php echo wp_kses_post( apply_filters( 'smarttoolz_sidebar_data_attrs', '', 'advanced-footer-widget-4' ) ); ?>>
					<?php smarttoolz_get_footer_widget( 'advanced-footer-widget-4' ); ?>
				</div>
			</div><!-- .ast-row -->
		</div><!-- .ast-container -->
	</div><!-- .footer-adv-overlay-->
</div><!-- .ast-theme-footer .footer-adv-layout-4 -->
