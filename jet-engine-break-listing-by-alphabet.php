<?php
/**
 * Plugin Name: JetEngine - break listing by alphabet
 * Plugin URI:
 * Description: Separate JetEngine listing by alphabet
 * Version:     1.0.0
 * Author:      Crocoblock
 * Author URI:  https://crocoblock.com/
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

class Jet_Engine_Break_Listing_By_Alphabet {

	public function __construct() {

		add_action( 'init', array( $this, 'setup' ) );
		add_action( 'jet-engine/listing/before-grid-item', array( $this, 'handle_item' ), 10, 2 );

	}

	/**
	 * These constants could be defined from functions.php file of your active theme
	 * @return [type] [description]
	 */
	public function setup() {

		if ( ! defined( 'JET_ENGINE_BREAK_ALPHABET_BY_QUERY_ID' ) ) {
			// set query ID to break by. Same query ID need to be set also for Listing and filter widgets if you using this in combination with JSF
			define( 'JET_ENGINE_BREAK_ALPHABET_BY_QUERY_ID', 'break_alphabet' );
		}

		if ( ! defined( 'JET_ENGINE_BREAK_ALPHABET_OPEN_HTML' ) ) {
			// set opening html tag(s) for alphabet letter
			define( 'JET_ENGINE_BREAK_ALPHABET_OPEN_HTML', '<h4 class="jet-engine-break-listing" style="width:100%; flex: 0 0 100%;">' );
		}

		if ( ! defined( 'JET_ENGINE_BREAK_ALPHABET_CLOSE_HTML' ) ) {
			// set closing html tag(s) for alphabet letter
			define( 'JET_ENGINE_BREAK_ALPHABET_CLOSE_HTML', '</h4>' );
		}

		if ( ! defined( 'JET_ENGINE_BREAK_ALPHABET_BY_PROP' ) ) {
			// set object property to get alphabet letter from. Set this to use with non-posts queries
			define( 'JET_ENGINE_BREAK_ALPHABET_BY_PROP', false );
		}

	}

	public function handle_item( $post, $listing ) {

		if ( empty( $listing->query_vars['request']['query_id'] ) ) {
			return;
		}

		$query = \Jet_Engine\Query_Builder\Manager::instance()->get_query_by_id( $listing->query_vars['request']['query_id'] );

		if ( ! $query ) {
			return;
		}

		if ( ! $query->query_id || JET_ENGINE_BREAK_ALPHABET_BY_QUERY_ID !== $query->query_id ) {
			return;
		}

		$index      = jet_engine()->listings->data->get_index();
		$query_type = $query->query_type;

		if ( $this->is_render_first( $index ) ) {
			$this->render_alphabet_letter( $post, $query_type );
		} else {
			$prev_post      = $this->get_prev_post( $index, $query );
			$prev_letter    = $this->get_first_letter( $prev_post, $query_type );
			$current_letter = $this->get_first_letter( $post, $query_type );

			if ( $prev_letter !== $current_letter ) {
				$this->render_alphabet_letter( $post, $query_type );
			}
		}
	}

	public function is_render_first( $index ) {

		// do not render first header on JetEngine Load More
		if ( jet_engine()->listings->is_listing_ajax( 'listing_load_more' ) ) {
			return false;
		}

		// do not render first header on JetSmartFilters Load More pagination
		if ( ! empty( $_REQUEST['action'] ) && 'jet_smart_filters' === $_REQUEST['action']
			 && ! empty( $_REQUEST['props'] ) && ! empty( $_REQUEST['props']['pages'] )
		) {
			return false;
		}

		return 0 === $index;
	}

	public function get_prev_post( $index, $query ) {

		if ( 0 === $index ) {
			$page = $query->get_current_items_page();

			$query->set_filtered_prop( '_page', $page - 1 );
			$query->reset_query();

			$items     = $query->get_items();
			$last_key  = array_key_last( $items );
			$prev_post = $items[ $last_key ];

			$query->set_filtered_prop( '_page', $page );
			$query->reset_query();

		} else {
			$items     = $query->get_items();
			$prev_post = $items[ $index - 1 ];
		}

		return $prev_post;
	}

	public function get_first_letter( $post, $query_type = null ) {

		$prop = false;

		if ( JET_ENGINE_BREAK_ALPHABET_BY_PROP ) {
			$prop = JET_ENGINE_BREAK_ALPHABET_BY_PROP;
		} else {
			switch ( $query_type ) {
				case 'posts':
					$prop = 'post_title';
					break;

				case 'terms':
					$prop = 'name';
					break;

				case 'users':
					$prop = 'display_name';
					break;
			}
		}

		if ( ! $prop || ! isset( $post->{$prop} ) ) {
			return false;
		}

		$title        = trim( $post->{$prop} );
		$first_letter = substr( $title, 0, 1 );

		if ( empty( $first_letter ) ) {
			return false;
		}

		return strtoupper( $first_letter );
	}

	public function render_alphabet_letter( $post, $query_type = null ) {

		$first_letter = $this->get_first_letter( $post, $query_type );

		if ( empty( $first_letter ) ) {
			return;
		}

		echo JET_ENGINE_BREAK_ALPHABET_OPEN_HTML;
		echo $first_letter;
		echo JET_ENGINE_BREAK_ALPHABET_CLOSE_HTML;

	}

}

new Jet_Engine_Break_Listing_By_Alphabet();
