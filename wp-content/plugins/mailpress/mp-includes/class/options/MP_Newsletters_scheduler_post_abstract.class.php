<?php
class MP_Newsletters_scheduler_post_abstract extends MP_Newsletters_scheduler_abstract
{
	function __construct($description)
	{
		parent::__construct($description);
		add_action('publish_post',	array(&$this, 'publish_post'), 8, 1);
	}

	function publish_post($post_id)
	{
		if (get_post_meta($post_id, '_MailPress_prior_to_install')) return true;

		$newsletters = MP_Newsletters::get_active_by_scheduler($this->id);
		if (empty($newsletters)) return true;

		$post = &get_post($post_id);
		$the_title = apply_filters('the_title', $post->post_title );

		$y = $this->year;
		$m = $this->month;
		$d = $this->day;

		$results = array();

		$trace = MP_Newsletters_schedulers::header_report("publish_post : {$post_id}    {$this->id}");
		foreach($newsletters as $newsletter)
		{
			$this->newsletter = $newsletter;

			$this->newsletter['mail']['the_title'] = $the_title;

			$this->newsletter['processor']['query_posts']['p'] = $post_id;

			$this->newsletter['params']['post_id']  = $post_id;
			$this->newsletter['params']['meta_key'] = $this->get_meta_key();
		
			$h = $this->hour  + $this->get_hour();
			$i = $this->minute+ $this->get_minute();

			$results[] = $this->schedule_single_event( $this->mktime( $h, $i, 0, $m, $d, $y ), 'mp_process_post_newsletter' );
		}

		if (!empty($results))
		{
			MP_Newsletters_schedulers::sep_report($trace);
			$results = array_reverse($results);
			uasort($results, create_function('$a, $b', 'return strcmp($a["timestamp"], $b["timestamp"]);'));
			foreach($results as $result) $trace->log($result['log']);
		}
		MP_Newsletters_schedulers::footer_report($trace);
	}
}