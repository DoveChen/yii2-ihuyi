<?php

	namespace dovechen\yii2\ihuyi\src\gateways;

	use dovechen\yii2\ihuyi\components\IhuyiComponent;
	use yii\base\InvalidConfigException;
	use yii\base\InvalidParamException;

	/**
	 * Class VoiceGateway
	 * @package dovechen\yii2\ihuyi\src\gateways
	 */
	class VoiceGateway extends IhuyiComponent
	{
		const SEND_URL = 'http://api.voice.ihuyi.com/webservice/voice.php?method=Submit';
		const GET_NUM_URL = 'http://api.voice.ihuyi.com/webservice/voice.php?method=GetNum';

		/**
		 * @inheritDoc
		 *
		 * @throws InvalidConfigException
		 */
		public function init ()
		{
			parent::init();
			if (empty($this->ihuyi->vsms['appid']) || empty($this->ihuyi->vsms['apikey'])) {
				throw new InvalidConfigException('The "appid" and "apikey" properties must be set.');
			}
		}

		/**
		 * Send voice sms.
		 *
		 * @param $modile
		 * @param $content
		 *
		 * @return mixed
		 */
		public function send ($mobile, $content)
		{
			if (empty($mobile) || empty($content)) {
				throw new InvalidParamException('The "mobile" and "content" propoerties must be set.');
			}

			$time     = time();
			$postData = [
				'account'  => $this->ihuyi->vsms['appid'],
				'password' => md5($this->ihuyi->vsms['appid'] . $this->ihuyi->vsms['apikey'] . $mobile . $content . $time),
				'mobile'   => $mobile,
				'content'  => $content,
				'time'     => $time,
			];

			return $this->ihuyi->httpPost(self::SEND_URL, $postData);
		}

		/**
		 * Get voice sms num.
		 *
		 * @return mixed
		 */
		public function getNum ()
		{
			$time     = time();
			$postData = [
				'account'  => $this->ihuyi->vsms['appid'],
				'password' => md5($this->ihuyi->vsms['appid'] . $this->ihuyi->vsms['apikey'] . $time),
				'time'     => $time,
			];

			return $this->ihuyi->httpPost(self::GET_NUM_URL, $postData);
		}
	}