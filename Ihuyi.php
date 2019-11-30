<?php

	namespace dovechen\yii2\ihuyi;

	use dovechen\yii2\ihuyi\components\BaseIhuyi;
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
		 * eg: ['appid' => '', 'appkey' => '']
		 *
		 * @var array
		 */
		public $sms = [];

		/**
		 * ihuyi config
		 * i-sms: international sms
		 * eg: ['appid' => '', 'appkey' => '']
		 *
		 * @var array
		 */
		public $isms = [];

		/**
		 * ihuyi config
		 * v-sms: voice sms
		 * eg: ['appid' => '', 'appkey' => '']
		 *
		 * @var array
		 */
		public $vsms = [];

		/**
		 * ihuyi config
		 * vn-sms: voice notice sms
		 * eg: ['appid' => '', 'appkey' => '']
		 *
		 * @var array
		 */
		public $vnsms = [];

		/**
		 * ihuyi config
		 * m-sms: marketing sms
		 * eg: ['appid' => '', 'appkey' => '']
		 *
		 * @var array
		 */
		public $msms = [];

		/**
		 * ihuyi config
		 * c-sms: color sms
		 * eg: ['appid' => '', 'appkey' => '']
		 *
		 * @var array
		 */
		public $csms = [];

		/**
		 * Marketing sms.
		 *
		 * @var Object
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
			}

			return $result;
		}

		/**
		 * Get marketing sms.
		 *
		 * @return object|Object
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