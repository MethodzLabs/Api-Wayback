<?php

namespace Methodz\Api\Wayback\Exception;


use Exception;
use Methodz\Helpers\Date\DateTime;
use Throwable;

class WaybackNotDataAvailableException extends Exception
{
	public function __construct(string $site_url, DateTime $datetime, int $code = 0, ?Throwable $previous = null)
	{
		parent::__construct("Invalid url : '$site_url' and date '" . $datetime->formatFrenchMax() . "'(" . $datetime->getTimestamp() . ")", $code, $previous);
	}
}
