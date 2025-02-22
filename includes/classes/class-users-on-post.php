<?php
/**
 * Assign users to posts.
 * A users with an account can (un-)assign herself.
 *
 * List all users who are assigned to a post.
 *
 * @package
 */
class Users_On_Post {

	/**
	 * Manage the ajax request.
	 *
	 * @return void
	 */
	public static function ajax() {
		// Validate nonce.
		if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( $_GET['nonce'], 'wp_rest' ) ) {
			wp_send_json_error( 'Invalid nonce.' );
		}

		// Validate tasks.
		$valid_tasks = array( 'get_all_users', 'add_user', 'remove_user' );
		$task = isset( $_GET['task'] ) ? $_GET['task'] : '';
		if ( ! in_array( $task, $valid_tasks ) ) {
			wp_send_json_error( 'Invalid action.' );
		}

		// Validate post ID.
		$post_id = isset( $_GET['post_id'] ) ? (int) $_GET['post_id'] : 0;
		if ( ! $post_id ) {
			wp_send_json_error( 'Empty post id.' );
		}

		// Validate user ID (if necessary)
		$user_id = isset( $_GET['user_id'] ) ? (int) $_GET['user_id'] : 0;
		if ( in_array( $task, array( 'add_user', 'remove_user' ) ) ) {
			if ( ! $user_id ) {
				wp_send_json_error( 'User ID can not be empty while adding or removing users.' );
			}
			if ( $user_id !== get_current_user_id() ) {
				wp_send_json_error( 'You can only add or remove yourself.' );
			}
		}

		$data = self::$task( $post_id, $user_id );
		if ( is_wp_error( $data ) ) {
			wp_send_json_error( $data->get_error_message() );
		} else {
			wp_send_json_success( $data );
		}

	}

	/**
	 * Lists all users that are assigned to a post.
	 * @todo WIP
	 *
	 * @param int $post_id
	 * @param int $user_id
	 * @return array|WP_Error
	 */
	public static function get_all_users( int $post_id, int $user_id = 0 ) {
		$users = get_field( 'students', $post_id );
		foreach ($users as $user) {
			$user['user_profile_url'] =  spaces()->blogs_profile->get_profile_url( $user['ID'] );
			$usersnew[] =$user; 
		}
		return $usersnew;
	}

	/**
	 * Add a user to a post.
	 * @todo WIP
	 *
	 * @param int $post_id
	 * @param int $user_id
	 * @return bool|WP_Error
	 */
	public static function add_user( int $post_id, int $user_id ) {
		$userexists = 0;
		$users = get_field( 'students', $post_id, FALSE);
		if (!empty ($users)) {
			foreach ($users as $user) {
				if ($user ==  $user_id){
					$userexists = 1;
					break;
				}
				else {
					$userexists = 0;
				}
			}
		}
		else {
			$users = array();
		}
		if ($userexists != 1) {
			$users[] = $user_id;
			// Update with new value.
			update_field( 'students', $users, $post_id );
		}
	
}

	/**
	 * Remove a user from a post.
	 * @todo WIP
	 *
	 * @param int $post_id
	 * @param int $user_id
	 * @return bool|WP_Error
	 */
	public static function remove_user( int $post_id, int $user_id ) {
		// Get the current value.
		$users = get_field( 'students', $post_id, FALSE);
		if (!empty ($users)) {
			foreach ($users as $key => $user) {
				if ( $user == $user_id ) {
					unset($users[$key]);
					break;
				}
			}
		}
		// Update with new value.
		update_field( 'students', $users, $post_id );
		
	}
}
