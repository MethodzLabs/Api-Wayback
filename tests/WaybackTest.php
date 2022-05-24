<?php


use Methodz\Api\Wayback\Wayback;
use PHPUnit\Framework\TestCase;

class WaybackTest extends TestCase
{

	public function testGetFirstDateTimeWaybackCapture()
	{
		$response = Wayback::getFirstDateTimeWaybackCapture('zaacom.fr');
		self::assertEquals(1381126434,$response->getTimestamp());
	}

	public function testGetWaybackCaptureData()
	{
		$response = Wayback::getWaybackCaptureData('zaacom.fr', Methodz\Helpers\Date\DateTime::now()->setTimestamp(0));
		self::assertEquals(20131007081354, $response['archived_snapshots']['closest']['timestamp']);
	}


	public function testGetWaybackCaptureDataInvalidUrl()
	{
		$this->expectException(\Methodz\Api\Wayback\Exception\WaybackNotDataAvailableException::class);
		Wayback::getWaybackCaptureData('zaaé"&com.fr', Methodz\Helpers\Date\DateTime::now()->setTimestamp(0));
	}

	public function testGetFirstDateTimeWaybackCaptureInvalidUrl()
	{
		$this->expectException(\Methodz\Api\Wayback\Exception\WaybackNotDataAvailableException::class);
		Wayback::getFirstDateTimeWaybackCapture('zaaé"&com.fr');
	}

}
