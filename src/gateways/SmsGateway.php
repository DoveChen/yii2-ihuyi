<?php

	namespace dovechen\yii2\ihuyi\src\gateways;

	use app\components\InvalidParameterException;
	use dovechen\yii2\ihuyi\components\IhuyiComponent;
	use yii\base\InvalidConfigException;

	/**
	 * Class SmsGateway
	 * @package dovechen\yii2\ihuyi\src\gateways
	 */
	class SmsGateway extends IhuyiComponent
	{
		const SEND_URL = 'http://106.ihuyi.cn/webservice/sms.php?method=Submit';
		const GET_NUM_URL = 'http://106.ihuyi.com/webservice/sms.php?method=GetNum';
		const ADD_TEMPLATE_URL = 'http://106.ihuyi.com/webservice/sms.php?method=AddTemplate';

		/**
		 * @inheritDoc
		 *
		 * @throws InvalidConfigException
		 */
		public function init ()
		{
			parent::init();
			if (empty($this->ihuyi->sms['appid']) || empty($this->ihuyi->sms['apikey'])) {
				throw new InvalidConfigException('The "appid" and "apikey" properties must be set.');
			}
		}

		/**
		 * Send normal sms.
		 *
		 * @param $modile
		 * @param $content
		 *
		 * @return mixed
		 * @throws InvalidParameterException
		 */
		public function send ($mobile, $content)
		{
			if (empty($mobile) || empty($content)) {
				throw new InvalidParameterException('The "mobile" and "content" propoerties must be set.');
			}

			$time     = time();
			$postData = [
				'account'  => $this->ihuyi->sms['appid'],
				'password' => md5($this->ihuyi->sms['appid'] . $this->ihuyi->sms['apikey'] . $mobile . $content . $time),
				'mobile'   => $mobile,
				'content'  => $content,
				'time'     => $time,
			];

			return $this->ihuyi->httpPost(self::SEND_URL, $postData);
		}

		/**
		 * Get normal sms num.
		 *
		 * @return mixed
		 */
		public function getNum ()
		{
			$time     = time();
			$postData = [
				'account'  => $this->ihuyi->sms['appid'],
				'password' => md5($this->ihuyi->sms['appid'] . $this->ihuyi->sms['apikey'] . $time),
				'time'     => $time,
			];

			return $this->ihuyi->httpPost(self::GET_NUM_URL, $postData);
		}

		/**
		 * Add normal sms template.
		 *
		 * @param $content
		 *
		 * @return mixed
		 * @throws InvalidParameterException
		 */
		public function addTemplate ($content)
		{
			if (empty($content)) {
				throw new InvalidParameterException('The  "content" propoerty must be set.');
			}

			$postData = [
				'account'  => $this->ihuyi->sms['appid'],
				'password' => md5($this->ihuyi->sms['apikey']),
				'content'  => $content,
			];

			return $this->ihuyi->httpPost(self::ADD_TEMPLATE_URL, $postData);
		}
	}