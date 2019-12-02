<?php

	namespace dovechen\yii2\ihuyi;

	use dovechen\yii2\ihuyi\components\BaseIhuyi;
	use dovechen\yii2\ihuyi\src\gateways\InternationalGateway;
	use dovechen\yii2\ihuyi\src\gateways\MarketingGateway;
	use dovechen\yii2\ihuyi\src\gateways\SmsGateway;
	use dovechen\yii2\ihuyi\src\gateways\VoiceGateway;
	use dovechen\yii2\ihuyi\src\gateways\VoiceNoticeGateway;
	use yii\base\InvalidConfigException;
	use yii\base\Object;
	use yii\helpers\ArrayHelper;

	/**
	 * Class Ihuyi
	 * @package dovechen\yii2\ihuyi
	 */
	class Ihuyi extends BaseIhuyi
	{
		const NORMAL_SMS = 1;
		const INTERNATIONAL_SMS = 2;
		const VOICE_SMS = 3;
		const VOICE_NOTICE_SMS = 4;
		const MARKETING_SMS = 5;
		const COLOR_SMS = 6;

		/**
		 * ihuyi config
		 * sms: normal sms
		 * eg: ['appid' => '', 'apikey' => '']
		 *
		 * @var array
		 */
		public $sms = [];

		/**
		 * ihuyi config
		 * i-sms: international sms
		 * eg: ['appid' => '', 'apikey' => '']
		 *
		 * @var array
		 */
		public $isms = [];

		/**
		 * ihuyi config
		 * v-sms: voice sms
		 * eg: ['appid' => '', 'apikey' => '']
		 *
		 * @var array
		 */
		public $vsms = [];

		/**
		 * ihuyi config
		 * vn-sms: voice notice sms
		 * eg: ['appid' => '', 'apikey' => '']
		 *
		 * @var array
		 */
		public $vnsms = [];

		/**
		 * ihuyi config
		 * m-sms: marketing sms
		 * eg: ['appid' => '', 'apikey' => '']
		 *
		 * @var array
		 */
		public $msms = [];

		/**
		 * ihuyi config
		 * c-sms: color sms
		 * eg: ['appid' => '', 'apikey' => '']
		 *
		 * @var array
		 */
		public $csms = [];

		/**
		 * Normal sms.
		 *
		 * @var SmsGateway
		 */
		private $_sms;

		/**
		 * International sms.
		 *
		 * @var InternationalGateway
		 */
		private $_isms;

		/**
		 * Voice sms.
		 *
		 * @var VoiceGateway
		 */
		private $_vsms;

		/**
		 * Voice notice sms.
		 *
		 * @var VoiceNoticeGateway
		 */
		private $_vnsms;

		/**
		 * Marketing sms.
		 *
		 * @var MarketingGateway
		 */
		private $_marketing;

		/**
		 * @inheritDoc
		 *
		 * @throws InvalidConfigException
		 */
		public function init ()
		{

			parent::init();

			if (empty($this->sms) && empty($this->isms) && empty($this->vsms) && empty($this->vnsms) && empty($this->msms) && empty($this->csms)) {
				throw new InvalidConfigException('The "sms"/"isms"/"vsms"/"vnsms"/"msms"/"csms" property must be last one set.');
			}
		}

		/**
		 * @inheritDoc
		 *
		 * @param callable $callable
		 * @param string   $url
		 * @param null     $postOptions
		 *
		 * @return array|bool|void
		 */
		public function parseHttpRequest (callable $callable, $url, $postOptions = NULL)
		{
			$result = [
				'error' => 0,
			];

			$response = call_user_func_array($callable, [$url, $postOptions]);
			$response = $this->xmlToArray($response);

			if (isset($response['SubmitResult'])) {
				if ($response['SubmitResult']['code'] != 2) {
					$result = [
						'error'     => $response['SubmitResult']['code'],
						'error_msg' => $response['SubmitResult']['msg'],
					];
				} else {
					if (!empty($response['SubmitResult']['smsid'])) {
						$result['smsid'] = $response['SubmitResult']['smsid'];
					}
				}
			} elseif (isset($response['GetNumResult'])) {
				if ($response['GetNumResult']['code'] != 2) {
					$result = [
						'error'     => $response['GetNumResult']['code'],
						'error_msg' => $response['GetNumResult']['msg'],
					];
				} else {
					if (!empty($response['GetNumResult']['num'])) {
						$result['num'] = $response['GetNumResult']['num'];
					}
				}
			} elseif (isset($response['AddTemplateResult'])) {
				if ($response['AddTemplateResult']['code'] != 2) {
					$result = [
						'error'     => $response['AddTemplateResult']['code'],
						'error_msg' => $response['AddTemplateResult']['msg'],
					];
				} else {
					if (!empty($response['AddTemplateResult']['templateid'])) {
						$result['templateid'] = $response['AddTemplateResult']['templateid'];
					}
				}
			}

			return $result;
		}

		/**
		 * Get normal sms.
		 *
		 * @return SmsGateway
		 *
		 * @throws InvalidConfigException
		 */
		public function getSms ()
		{
			if ($this->_sms === NULL) {
				$this->_sms = \Yii::createObject('dovechen\yii2\ihuyi\src\gateways\SmsGateway', [$this]);
			}

			return $this->_sms;
		}

		/**
		 * Get international sms.
		 *
		 * @return InternationalGateway
		 *
		 * @throws InvalidConfigException
		 */
		public function getInternational ()
		{
			if ($this->_isms === NULL) {
				$this->_isms = \Yii::createObject('dovechen\yii2\ihuyi\src\gateways\InternationalGateway', [$this]);
			}

			return $this->_isms;
		}

		/**
		 * Get voice sms.
		 *
		 * @return VoiceGateway
		 *
		 * @throws InvalidConfigException
		 */
		public function getVoice ()
		{
			if ($this->_vsms === NULL) {
				$this->_vsms = \Yii::createObject('dovechen\yii2\ihuyi\src\gateways\VoiceGateway', [$this]);
			}

			return $this->_vsms;
		}

		/**
		 * Get voice notice sms.
		 *
		 * @return VoiceNoticeGateway
		 *
		 * @throws InvalidConfigException
		 */
		public function getVoiceNotice ()
		{
			if ($this->_vnsms === NULL) {
				$this->_vnsms = \Yii::createObject('dovechen\yii2\ihuyi\src\gateways\VoiceNoticeGateway', [$this]);
			}

			return $this->_vsms;
		}

		/**
		 * Get marketing sms.
		 *
		 * @return MarketingGateway
		 *
		 * @throws InvalidConfigException
		 */
		public function getMarketing ()
		{
			if ($this->_marketing === NULL) {
				$this->_marketing = \Yii::createObject('dovechen\yii2\ihuyi\src\gateways\MarketingGateway', [$this]);
			}

			return $this->_marketing;
		}

		/**
		 * Send normal sms.
		 *
		 * @param $mobile
		 * @param $content
		 *
		 * @return mixed
		 *
		 * @throws InvalidConfigException
		 */
		public function sendSms ($mobile, $content)
		{
			return $this->getSms()->send($mobile, $content);
		}

		/**
		 * Get normal num.
		 *
		 * @return mixed
		 *
		 * @throws InvalidConfigException
		 */
		public function getSmsNum ()
		{
			return $this->getSms()->getNum();
		}

		/**
		 * Add normal sms template.
		 *
		 * @param $content
		 *
		 * @return mixed
		 *
		 * @throws InvalidConfigException
		 */
		public function addSmsTemplate ($content)
		{
			return $this->getSms()->addTemplate($content);
		}

		/**
		 * Send international sms.
		 *
		 * @param $mobile
		 * @param $content
		 *
		 * @return mixed
		 *
		 * @throws InvalidConfigException
		 */
		public function sendInternational ($mobile, $content)
		{
			return $this->getInternational()->send($mobile, $content);
		}

		/**
		 * Get international num.
		 *
		 * @return mixed
		 *
		 * @throws InvalidConfigException
		 */
		public function getInternationalNum ()
		{
			return $this->getInternational()->getNum();
		}

		/**
		 * Send voice sms.
		 *
		 * @param $mobile
		 * @param $content
		 *
		 * @return mixed
		 *
		 * @throws InvalidConfigException
		 */
		public function sendVoice ($mobile, $content)
		{
			return $this->getVoice()->send($mobile, $content);
		}

		/**
		 * Get voice num.
		 *
		 * @return mixed
		 *
		 * @throws InvalidConfigException
		 */
		public function getVoiceNum ()
		{
			return $this->getVoice()->getNum();
		}

		/**
		 * Send voice notice sms.
		 *
		 * @param $mobile
		 * @param $content
		 *
		 * @return mixed
		 *
		 * @throws InvalidConfigException
		 */
		public function sendVoiceNotice ($mobile, $content)
		{
			return $this->getVoiceNotice()->send($mobile, $content);
		}

		/**
		 * Get voice notice num.
		 *
		 * @return mixed
		 *
		 * @throws InvalidConfigException
		 */
		public function getVoiceNoticeNum ()
		{
			return $this->getVoiceNotice()->getNum();
		}

		/**
		 * Send marketing sms.
		 *
		 * @param $mobile
		 * @param $content
		 *
		 * @return mixed
		 *
		 * @throws InvalidConfigException
		 */
		public function sendMarketing ($mobile, $content)
		{
			return $this->getMarketing()->send($mobile, $content);
		}

		/**
		 * Get marketing num.
		 *
		 * @return mixed
		 *
		 * @throws InvalidConfigException
		 */
		public function getMarketingNum ()
		{
			return $this->getMarketing()->getNum();
		}

	}