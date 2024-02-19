<?php


namespace EMedia\Helpers\Email;


class Webmaster
{

	/**
	 *
	 * Send a mail to the site's webmaster
	 *
	 * @param        $message
	 * @param string $subject
	 */
	public static function sendEmail($message, $subject = 'SYSTEM MESSAGE')
	{
		$mailBody = [];
		$mailBody[] = "\r\n";
		$mailBody[] = $subject;
		$mailBody[] = "-------------------------------------------\r\n";
		$mailBody[] = $message;
		$mailBody[] = "-------------------------------------------\r\n";

		$mailContent = implode("\r\n", $mailBody);

		$recepients = env('WEBMASTER_EMAIL');
		if (empty($recepients)) {
			throw new \InvalidArgumentException("Webmaster recepient emails not set in WEBMASTER_EMAIL");
		}

		$webmasterReplyTo = env('WEBMATER_REPLY_TO');

		$recepients = explode('|', $recepients);

		// Method 1
		\Mail::raw($mailContent, function($message) use ($subject, $recepients, $webmasterReplyTo)
		{
			$message->subject($subject);
			if (!empty($webmasterReplyTo)) {
				$message->replyTo($webmasterReplyTo);
			}
			$message->to($recepients);
		});
	}

}