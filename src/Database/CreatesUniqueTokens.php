<?php


namespace EMedia\Helpers\Database;


use EMedia\Helpers\Exceptions\Auth\TokenGenerationException;

trait CreatesUniqueTokens
{


	/**
	 *
	 * Create a unique token for a given Database field.
	 *
	 * @param     $dbFieldName
	 * @param int $length
	 *
	 * @return null|string
	 * @throws TokenGenerationException
	 */
	public static function newUniqueToken($dbFieldName, $length = 35)
	{
		// take the timestamp and merge with a random string
		// unlikely to cause a collision unless there's very high traffic
		// repeat iMax times and fail

		$randomToken = null;
		$iMax = 10;

		for ($i = 0; $i < $iMax; $i++) {
			$randomToken = time() . \Illuminate\Support\Str::random($length);
			$existing = self::where($dbFieldName, $randomToken)->first();
			if (!$existing) return $randomToken;
		}

		throw new TokenGenerationException("Failed to create a unique token. Failed after trying $iMax times.");
	}

}
