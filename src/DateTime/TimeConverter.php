<?php


namespace EMedia\Helpers\DateTime;


class TimeConverter
{

	/**
	 *
	 * Convert a UTC time-string to the applications timezone.
	 * Useful to convert JavaScript date strings to Carbon dates.
	 *
	 * @param $UTCTimeString
	 *
	 * @return \Carbon\Carbon
	 */
	public static function toServerTimezone($UTCTimeString, $onlyDate = false)
	{
		// convert the UTC date sent by client to our format
		$reportDate = new \Carbon\Carbon($UTCTimeString);
		$reportDate->setTimezone(config('app.timezone', 'UTC'));
		if ($onlyDate) $reportDate->startOfDay();
		return $reportDate;
	}

}