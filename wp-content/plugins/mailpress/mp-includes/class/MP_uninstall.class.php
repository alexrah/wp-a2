<?php
class MP_uninstall
{
	function __construct()
	{
		global $wpdb;

// taxonomies
		$taxonomies = array('MailPress_mailing_list', 'MailPress_autoresponder');
		foreach($taxonomies as $taxonomy)
		{
			$queries[] = "DELETE FROM $wpdb->terms              WHERE term_id IN (SELECT term_id FROM $wpdb->term_taxonomy WHERE taxonomy = '$taxonomy');";
			$queries[] = "DELETE FROM $wpdb->term_relationships WHERE term_id IN (SELECT term_id FROM $wpdb->term_taxonomy WHERE taxonomy = '$taxonomy');";
			$queries[] = "DELETE FROM $wpdb->term_taxonomy WHERE taxonomy = '$taxonomy';";
		}
// postmeta
		$queries[] = "DELETE FROM $wpdb->postmeta WHERE meta_key like '%_MailPress%';";		
		$queries[] = "DELETE FROM $wpdb->postmeta WHERE meta_key like '%_mailpress%';";
// usermeta
		$queries[] = "DELETE FROM $wpdb->usermeta WHERE meta_key like '%_MailPress%';";		
		$queries[] = "DELETE FROM $wpdb->usermeta WHERE meta_key like '%_mailpress%';";
// options
		$queries[] = "DELETE FROM $wpdb->options WHERE option_name like '%MailPress%';";		
		$queries[] = "DELETE FROM $wpdb->options WHERE option_name like '%mailpress%';";
// mailpress tables
		$queries[] = "DROP TABLE $wpdb->mp_stats;";
		$queries[] = "DROP TABLE $wpdb->mp_mails;";
		$queries[] = "DROP TABLE $wpdb->mp_mailmeta;";		
		$queries[] = "DROP TABLE $wpdb->mp_users;";		
		$queries[] = "DROP TABLE $wpdb->mp_usermeta;";
		$queries[] = "DROP TABLE $wpdb->mp_tracks;";	
		$queries[] = "DROP TABLE $wpdb->mp_forms;";	
		$queries[] = "DROP TABLE $wpdb->mp_fields;";	

		foreach($queries as $query) $wpdb->query($query);
	}
}