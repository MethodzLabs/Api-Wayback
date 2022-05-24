<?php

namespace Methodz\Api\Wayback;

use Methodz\Api\Wayback\Exception\WaybackNotDataAvailableException;
use Methodz\Api\Wayback\Exception\WaybackStatusResponsesCodeException;
use Methodz\Helpers\Curl\Curl;
use Methodz\Helpers\Curl\Exception\CurlResultCodeException;
use Methodz\Helpers\Date\DateTime;

abstract class Wayback
{

	/**
	 * This function returns the date when the first snapshot was recorded for a given url.
	 *
	 * @param string $url - the url of the site you want to get the timestamp (example.fr)
	 *
	 * @return DateTime
	 *
	 * @throws CurlResultCodeException
	 * @throws WaybackNotDataAvailableException
	 * @throws WaybackStatusResponsesCodeException
	 */
	public static function getFirstDateTimeWaybackCapture(string $url): DateTime
	{
		$dataTimeWaybackCapture = self::getWaybackCaptureData($url, DateTime::now()->setTimestamp(0));
		$datetimeString = $dataTimeWaybackCapture['archived_snapshots']['closest']['timestamp'];
		return DateTime::createFromFormat('YmdHis', $datetimeString);
	}

	/**
	 * This function returns the data in array format from the Wayback response for a given url.
	 *
	 * @param string   $url      - the url of the site you want to get the timestamp (example.fr)
	 * @param DateTime $datetime - the date on which we want to retrieve the timestamp
	 *
	 * @return array
	 *
	 * @throws CurlResultCodeException
	 * @throws WaybackStatusResponsesCodeException
	 * @throws WaybackNotDataAvailableException
	 */
	public static function getWaybackCaptureData(string $url, DateTime $datetime): array
	{
		Curl::init("http://archive.org/wayback/available");
		Curl::addGETParameters('url', urlencode($url));
		Curl::addGETParameters('timestamp', $datetime->getTimestamp());
		Curl::exec();
		$response = Curl::getResult();
		$curl_infos = Curl::getInfos();
		Curl::close();
		if ($curl_infos['http_code'] !== 200) {
			throw new CurlResultCodeException($curl_infos['http_code']);
		}

		$response = json_decode($response, true);
		if (array_key_exists('archived_snapshots', $response)) {
			if (array_key_exists('closest', $response['archived_snapshots'])) {
				if (array_key_exists('status', $response['archived_snapshots']['closest'])) {
					if (($status = intval($response['archived_snapshots']['closest']['status'])) !== 200) {
						throw new WaybackStatusResponsesCodeException($status, $url, $datetime);
					}
					return $response;
				}
			}
		}
		throw new WaybackNotDataAvailableException($url, $datetime);
	}

}
