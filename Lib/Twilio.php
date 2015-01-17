<?php
App::import('Vendor', 'Twilio.twilio/Services/Twilio');

/**
 * Twilio class
 *
 * This class is used for interacting with Twilio web services.
 */
class Twilio
{

/**
 * Reference to a Twilio client.
 *
 * @var array
 */
	protected $_twilio;

/**
 * Configuration settings
 *
 * @var array
 */
	protected $_config = array();
	
/**
 * Constructor
 *
 * @param array|string $config Array of configs, or string to load configs from twilio.php
 */
	public function __construct($config = null) 
	{
		$this->_init($config);
		$this->_twilio = new Services_Twilio($this->_config['AccountSid'], $this->_config['AuthToken']);
	}

/**
 * Configuration to use with Twilio
 *
 * ### Usage
 *
 * Load configuration from `app/Config/twilio.php`:
 *
 * `new Twilio();`
 *
 * Load configuration from an array into the instance:
 *
 * `new Twilio(array('AccountSid' => '...', 'AuthToken' => '...'));`
 *
 * @param string|array $config String with configuration name (from twilio.php), array with config or null to return current config
 * @return string|array|$this
 */
	protected function _init($config = null) 
	{

		if(!is_array($config)) 
		{
			if (!config('twilio')) {
				throw new ConfigureException(__d('cake_dev', '%s not found.', APP . 'Config' . DS . $config . 'twilio.php'));
			}

			// Load config
			Configure::load('twilio');
			if(!Configure::check('Twilio.AccountSid') || !Configure::check('Twilio.AuthToken'))
			{
				throw new ConfigureException(__d('cake_dev', 'Invalid Twilio configuration "%s".', $config));
			}

			$config = Configure::read('Twilio');
		}
		else
		{
			if(empty($config['AccountSid']) || empty($config['AuthToken']))
			{
				throw new ConfigureException(__d('cake_dev', 'Missing AccountSid or AuthToken in Twilio configuration.'));
			}
		}

		$this->_config = $config + $this->_config;
	}

/**
 * Sends SMS message
 *
 * @param string $to Mobile number of SMS message recipient
 * @param string $message SMS message to be sent
 */
	public function sendSms($to, $from, $message)
	{
		$sms = $this->_twilio->account->sms_messages->create(
		    $from, // From this number
		    $to, // To this number
		    $message
		);

		return $sms;
	}
}