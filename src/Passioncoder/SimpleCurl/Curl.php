<?php

namespace Passioncoder\SimpleCurl;


class Curl {


    /**
     * Send a GET request
     *
     * @param string $url      A valid URL
     * @param array  $params   Parameters as key/value pairs
     * @param array  $options  Curl options
     *
     * @return object The Response including header and body
     * @throws Passioncoder\SimpleCurl\Exception
     */
	public function get($url, array $params = null, array $options = array())
	{
		return $this->request($url, 'GET', $params, $options);
	}


    /**
     * Send a POST request
     *
     * @param string $url      A valid URL
     * @param array  $params   Parameters as key/value pairs
     * @param array  $options  Curl options
     *
     * @return object The Response including header and body
     * @throws Passioncoder\SimpleCurl\Exception
     */
	public function post($url, array $params = null, array $options = array())
	{
		return $this->request($url, 'POST', $params, $options);
	}


    /**
     * Send a HTTP request
     *
     * @param string $url      A valid URL
     * @param string $method   HTTP method (GET|POST)
     * @param array  $params   Parameters as key/value pairs
     * @param array  $options  Curl options
     *
     * @return object The Response including header and body
     * @throws Passioncoder\SimpleCurl\Exception
     */
	public function request($url, $method = 'GET', array $params = null, array $options = array())
	{
		$c = curl_init();

		// disable ssl verification (can be overwritten via options)
		curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);

		$query = !empty($params) ? http_build_query($params, '', '&', PHP_QUERY_RFC1738) : null;

		switch (strtolower($method)) {

			case 'get':

				if ($query) {

					$url .= '?' . $query;
				}

				curl_setopt($c, CURLOPT_HTTPGET, 1);

				break;

			case 'post':

				if ($query) {

					curl_setopt($c, CURLOPT_POSTFIELDS, $query);
				}

				// check if a http header is set via options and add the proper content-type for our query.

				if (isset($options[CURLOPT_HTTPHEADER]) && is_array($options[CURLOPT_HTTPHEADER])) {

					$options[CURLOPT_HTTPHEADER] = array_merge($options[CURLOPT_HTTPHEADER], ['content-type: application/x-www-form-urlencoded']);
				
				} else {

					$options[CURLOPT_HTTPHEADER] = ['content-type: application/x-www-form-urlencoded'];
				}
				
				curl_setopt($c, CURLOPT_POST, 1);
				
				break;

			default:

				throw new Exception('HTTP method ' . $method . ' is not supported.');
				break;
		}

		if (!empty($options)) {

			curl_setopt_array($c, $options);
		}

		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_AUTOREFERER, 1);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

		$body = curl_exec($c);
		
		if ($body === false) {
	
			$error = curl_error($c);
			$code = curl_errno($c);
			curl_close($c);
			throw new Exception($error, $code);
		}
		
		$header = (object) curl_getinfo($c);
		
		curl_close($c);

		if (strpos($header->content_type, '/json') !== false) {

			$body = json_decode($body);
		}
		
		return (object) [
		
			'header' =>	$header,
			'body'	 =>	$body,
		];
	}

}
