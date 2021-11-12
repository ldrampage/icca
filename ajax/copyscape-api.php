<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    //include external files
    require_once '../core/iccaFunctions2-new.php';
    $icca_new_obj = new iccaFunc2New();
    
    $creds = $icca_new_obj->getCredentials("WHERE api = 'CopyScape'");
    $creds = $creds[0];

	define('COPYSCAPE_USERNAME', $creds['username']);
	define('COPYSCAPE_API_KEY', $creds['api_key']);
	define('COPYSCAPE_API_URL', 'http://www.copyscape.com/api/');
	
    if (isset($_GET['rewrite'])) {
        echo json_encode(copyscape_api_text_search_internet($_GET['rewrite'], 'ISO-8859-1', 2));
        flush();
	}

	function copyscape_api_url_search_internet($url, $full=null) {
		return copyscape_api_url_search($url, $full, 'csearch');
	}
	
	function copyscape_api_text_search_internet($text, $encoding, $full=null) {
		return copyscape_api_text_search($text, $encoding, $full, 'csearch');
	}
	
	function copyscape_api_check_balance() {
		return copyscape_api_call('balance');
	}

	function copyscape_api_url_search($url, $full=null, $operation='csearch') {
		$params['q']=$url;

		if (isset($full))
			$params['c']=$full;
		
		return copyscape_api_call($operation, $params, array(2 => array('result' => 'array')));
	}
	
	function copyscape_api_text_search($text, $encoding, $full=null, $operation='csearch') {
		$params['e']=$encoding;

		if (isset($full))
			$params['c']=$full;

		return copyscape_api_call($operation, $params, array(2 => array ('result' => 'array')), $text);
	}

	function copyscape_api_call($operation, $params=array(), $xmlspec=null, $postdata=null) {
		$url=COPYSCAPE_API_URL.'?u='.urlencode(COPYSCAPE_USERNAME).
			'&k='.urlencode(COPYSCAPE_API_KEY).'&o='.urlencode($operation);
		
		foreach ($params as $name => $value)
			$url.='&'.urlencode($name).'='.urlencode($value);
			
		//for testing
		//$url .= '&x=1';
		
		$curl=curl_init();
		
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, isset($postdata));
		
		if (isset($postdata))
			curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
		
		$response=curl_exec($curl);
		curl_close($curl);
		
		if (strlen($response))
			return copyscape_read_xml($response, $xmlspec);
		else
			return false;
	}
	
	function copyscape_read_xml($xml, $spec=null) {
		global $copyscape_xml_data, $copyscape_xml_depth, $copyscape_xml_ref, $copyscape_xml_spec;
		
		$copyscape_xml_data=array();
		$copyscape_xml_depth=0;
		$copyscape_xml_ref=array();
		$copyscape_xml_spec=$spec;
		
		$parser=xml_parser_create();
		
		xml_set_element_handler($parser, 'copyscape_xml_start', 'copyscape_xml_end');
		xml_set_character_data_handler($parser, 'copyscape_xml_data');
		
		if (!xml_parse($parser, $xml, true))
			return false;
			
		xml_parser_free($parser);
		
		return $copyscape_xml_data;
	}

	function copyscape_xml_start($parser, $name, $attribs) {
		global $copyscape_xml_data, $copyscape_xml_depth, $copyscape_xml_ref, $copyscape_xml_spec;
		
		$copyscape_xml_depth++;
		
		$name=strtolower($name);
		
		if ($copyscape_xml_depth==1)
			$copyscape_xml_ref[$copyscape_xml_depth]=&$copyscape_xml_data;
		
		else {
			if (!is_array($copyscape_xml_ref[$copyscape_xml_depth-1]))
				$copyscape_xml_ref[$copyscape_xml_depth-1]=array();
				
			if (@$copyscape_xml_spec[$copyscape_xml_depth][$name]=='array') {
				if (!is_array(@$copyscape_xml_ref[$copyscape_xml_depth-1][$name])) {
					$copyscape_xml_ref[$copyscape_xml_depth-1][$name]=array();
					$key=0;
				} else
					$key=1+max(array_keys($copyscape_xml_ref[$copyscape_xml_depth-1][$name]));
				
				$copyscape_xml_ref[$copyscape_xml_depth-1][$name][$key]='';
				$copyscape_xml_ref[$copyscape_xml_depth]=&$copyscape_xml_ref[$copyscape_xml_depth-1][$name][$key];

			} else {
				$copyscape_xml_ref[$copyscape_xml_depth-1][$name]='';
				$copyscape_xml_ref[$copyscape_xml_depth]=&$copyscape_xml_ref[$copyscape_xml_depth-1][$name];
			}
		}
	}

	function copyscape_xml_end($parser, $name) {
		global $copyscape_xml_depth, $copyscape_xml_ref;
		
		unset($copyscape_xml_ref[$copyscape_xml_depth]);

		$copyscape_xml_depth--;
	}
	
	function copyscape_xml_data($parser, $data) {
		global $copyscape_xml_depth, $copyscape_xml_ref;

		if (is_string($copyscape_xml_ref[$copyscape_xml_depth]))
			$copyscape_xml_ref[$copyscape_xml_depth].=$data;
	}
	
?>