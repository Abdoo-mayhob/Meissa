<?php
/*
 *
 * All "Newsletter" releated Codes.
 * 
 */

define('MEISSA_NEWSLETTER_SUBS_TABLE' , 'meissa_newsletter_subs');

add_action('after_switch_theme', 'meissa_newsletter_subs_init_db');
function meissa_newsletter_subs_init_db() {
	global $wpdb;

	$table_name = $wpdb->prefix . MEISSA_NEWSLETTER_SUBS_TABLE;
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		email varchar(255) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

}


add_action( 'wp_ajax_meissa_newsletter_subs_add_sub', 'meissa_newsletter_subs_add_sub' );
add_action( 'wp_ajax_nopriv_meissa_newsletter_subs_add_sub', 'meissa_newsletter_subs_add_sub' );
function meissa_newsletter_subs_add_sub() {

	// Get Email and Validate
	$email = $_POST['email'];
	$email = is_email(sanitize_email($email));
	if($email === false){
		$reponse = [
			"status"  => 403,
			"message" => "هذا الايميل غير صالح, حاول بايميل اّخر",
			"email"   => $email,
			"debug"   => 'email validation faild'
		];
		echo json_encode($reponse);
		die;
	}

	// Check if email already exits
	global $wpdb;
	$table_name = $wpdb->prefix . MEISSA_NEWSLETTER_SUBS_TABLE;
	$query = "SELECT * FROM {$table_name} WHERE email = '$email'";
	$result = $wpdb->get_results($query);
	if(count($result) !== 0){
		$reponse = [
			"status"  => 409,
			"message" => "هذا الايميل موجود مسبقاً !",
			"email"   => $email
		];
		echo json_encode($reponse);
		die;
	}

	$debug = $wpdb->insert( 
		$table_name, 
		[
			'time' => current_time( 'mysql' ), 
			'email' => $email, 
		] 
	);
	$reponse = [
		"status"  => 200,
		"message" => "تم التسجيل في النشرة البريدية",
		"email"   => $email,
		"debug"   => $debug
	];
	echo json_encode($reponse);
	die;
}


/*
function meissa_newsletter_subs_remove_sub() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'liveshoutbox';

	$wpdb->insert( 
		$table_name, 
		[
			'time' => current_time( 'mysql' ), 
			'email' => $welcome_name, 
		] 
	);
}*/