<?php
class MP_Ip_geoplugin extends MP_Ip_provider_abstract
{
	var $id 	= 'geoplugin';
	var $url	= 'http://www.geoplugin.net/xml.gp?ip=%1$s';
	var $credit	= 'http://www.geoplugin.net/';
	var $type 	= 'xml';

	function content($valid, $content)
	{
		if (strpos($content, '<geoplugin_latitude></geoplugin_latitude>') !== false) return false;
		return $valid;
	}

	function data($content, $ip)
	{
		$skip = array('geoplugin_areaCode', 'geoplugin_dmaCode', 'geoplugin_continentCode', 'geoplugin_currencyCode', 'geoplugin_currencySymbol', 'geoplugin_currencyConverter');
		$html = '';

		$xml = $this->xml2array( $content );
		foreach ($xml as $k => $v)
		{
			if (empty($v))   continue;
			if ($v == 'n/a') continue;

			if (in_array($k, $skip)) continue;

			if (in_array($k, array('geoplugin_region', 'geoplugin_countryCode', 'geoplugin_latitude', 'geoplugin_longitude'))) {$$k = $v; continue;}

			$html .= "<p style='margin:3px;'><b>" . str_replace('geoplugin_', '', $k) . "</b> : $v</p>";
		}
		$geo = (isset($geoplugin_latitude) && isset($geoplugin_longitude)) ? 	array('lat' => $geoplugin_latitude, 'lng' => $geoplugin_longitude) : array();
		$country = (isset($geoplugin_countryCode)) ? $geoplugin_countryCode : '';
		$subcountry = (isset($geoplugin_region))   ? $geoplugin_region : '';
		return $this->cache_custom($ip, $geo, strtoupper(substr($country, 0, 2)), strtoupper($subcountry), $html);
	}
}
new MP_Ip_geoplugin();