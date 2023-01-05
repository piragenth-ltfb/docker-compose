<?php
/**
 * Opt_In_Condition_Wc_Archive_Pages.
 *
 * @package Hustle
 * @since unkwnown
 */

/**
 * Opt_In_Condition_Wc_Archive_Pages.
 * Condition based on the WooCommerce archive pages.
 *
 * @since unkwnown
 */
class Opt_In_Condition_Wc_Archive_Pages extends Opt_In_Condition_Abstract {

	/**
	 * Returns whether the condition was met.
	 *
	 * @since unkwnown
	 * @since 4.1.0 This functionality has been changed to affect WooCommerce Archive Pages (is_shop, is_product_tag, is_product_category.
	 */
	public function is_allowed() {
		if ( ! Opt_In_Utils::is_woocommerce_active() ) {
			return false;
		}

		if ( isset( $this->args->wc_archive_pages ) ) {
			$archive_pages = (array) $this->args->wc_archive_pages;

			if ( is_product_tag() ) {
				$allowed = in_array( 'is_product_tag', $archive_pages, true );
			} elseif ( is_product_category() ) {
				$allowed = in_array( 'is_product_category', $archive_pages, true );
			} elseif ( is_shop() ) {
				$allowed = in_array( 'is_shop', $archive_pages, true );
			}

			if ( ! isset( $allowed ) ) {
				return false;
			}

			if ( 'except' === $this->args->filter_type ) {
				return ! $allowed;
			} elseif ( 'only' === $this->args->filter_type ) {
				return $allowed;
			}
		}
		return false;
	}

}