<?php

namespace Methodz\Api\Wayback\Exception;


use Exception;
use Methodz\Helpers\Date\DateTime;
use Throwable;

class WaybackStatusResponsesCodeException extends Exception
{
	public function __construct(int $status_code, string $site_url, DateTime $datetime, int $code = 0, ?Throwable $previous = null)
	{
		parent::__construct("Error ($status_code) during request for site '$site_url' and date '" . $datetime->formatFrenchMax() . "'(" . $datetime->getTimestamp() . ")", $code, $previous);
	}
}
