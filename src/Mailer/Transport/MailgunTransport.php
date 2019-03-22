<?php

namespace MailgunEmail\Mailer\Transport;

use Cake\Mailer\AbstractTransport;
use Cake\Mailer\Email;
use Mailgun;
use Cake\Core\Exception\Exception;

/**
 * Send mail using Mailgun
 * @author Manish Motwani
 */
class MailgunTransport extends AbstractTransport
{

	/**
	 * Email header to Mailgun param mapping
	 *
	 * @var array
	 */
	private $ParamMapping = array(
	    'From' => 'from',
	    'Sender' => 'sender',
	    'Reply-To' => 'h:Reply-To',
	    'Disposition-Notification-To' => 'h:Disposition-Notification-To',
	    'Return-Path' => 'h:Return-Path',
	    'To' => 'to',
	    'Cc' => 'cc',
	    'Bcc' => 'bcc',
	    'Subject' => 'subject',
	    'o:tag' => 'o:tag',
	    'o:campaign' => 'o:campaign',
	    'o:deliverytime' => 'o:deliverytime',
	    'o:dkim' => 'o:dkim',
	    'o:testmode' => 'o:testmode',
	    'o:tracking' => 'o:tracking',
	    'o:tracking-clicks' => 'o:tracking-clicks',
	    'o:tracking-opens' => 'o:tracking-opens'
	);
	private $MailgunCustomDataPrefix = 'v:';

	/**
	 * Send email via Mailgun SDK
	 *
	 * @param Email $email
	 * @return \stdClass $result containing status code and message
	 * @throws Exception
	 */
	public function send(Email $email)
	{
		$config = $email->profile();
		$email->domain($config['mailgun_domain']);

		$emailHeaders = ['from', 'sender', 'replyTo', 'readReceipt', 'returnPath', 'to', 'cc', 'bcc', 'subject', '_headers'];
		//'_headers' will include all extra tags that may be related to mailgun fields with prefix 'o:' or custom data with prefix 'v:'

		foreach ($email->getHeaders($emailHeaders) as $header => $value) {
			if (isset($this->ParamMapping[$header]) && !empty($value)) { //empty params are not excepted by mailgun, throws error
				$key = $this->ParamMapping[$header];
				$params[$key] = $value;
				continue;
			}

			if ($this->isDataCustom($header, $value)) {
				$params[$header] = $value;
			}
		}

		$params['html'] = $email->message(Email::MESSAGE_HTML);
		$params['text'] = $email->message(Email::MESSAGE_TEXT);

		$attachments = array();
		foreach ($email->attachments() as $name => $file) {
			$attachments['attachment'][] = ['filePath' => '@' . $file['file'], 'remoteName' => $name];
		}

		return $this->mailgun($config, $params, $attachments);
	}

	/**
	 * Connect and submit email to Mailgun API Endpoint
	 *
	 * @param array $config API endpoint credentials
	 * @param array $params params as per mailgun key value format
	 * @param array $attachments attachments as per mailgun format
	 * @return \stdClass $result containing status code and message
	 * @throws Exception
	 */
	private function mailgun($config, $params, $attachments)
	{
		if (empty($config['mailgun_api_key']) || empty($config['mailgun_domain'])) {
			throw new Exception('Mailgun API Key & Domain cannot be empty');
		}

		try {
			if (isset($config['mailgun_postbin_id']) && !empty($config['mailgun_postbin_id'])) {
				$mailgun = new Mailgun\Mailgun($config['mailgun_api_key'], 'bin.mailgun.net', $config['mailgun_postbin_id'], false);
			} else {
				$mailgun = new Mailgun\Mailgun($config['mailgun_api_key']);
			}

			$result = $mailgun->sendMessage($config['mailgun_domain'], $params, $attachments);

			if ($result->http_response_code != 200) {
				throw new Exception($result->http_response_body->message);
			}
		} catch (Exception $exc) {
			throw $exc;
		}

		return $result;
	}

	/**
	 * To check valid Custom data format
	 *
	 * @param type $header
	 * @param type $value
	 * @return boolean
	 */
	private function isDataCustom($header, $value)
	{
		$return = false;

		if (strpos($header, $this->MailgunCustomDataPrefix) === 0 && !empty($value)) {
			$json = json_decode($value);
			if (!is_null($json) && json_last_error() === JSON_ERROR_NONE) { //Custom data must be a valid json
				$return = true;
			}
			// if custom data detected, but not valid json, then skip, do not throw error as mailgun does.
		}

		return $return;
	}

}