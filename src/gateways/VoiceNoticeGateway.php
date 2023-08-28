<?php

	namespace dovechen\yii2\ihuyi\src\gateways;

	use app\components\InvalidParameterException;
	use dovechen\yii2\ihuyi\components\IhuyiComponent;
	use yii\base\InvalidConfigException;

	/**
	 * Class VoiceNoticeGateway
	 * @package dovechen\yii2\ihuyi\src\gateways
	 */
	class VoiceNoticeGateway extends IhuyiComponent
	{
		const SEND_URL = 'http://api.vm.ihuyi.com/webservice/voice.php?method=Submit';
		const GET_NUM_URL = 'http://api.vm.ihuyi.com/webservice/voice.php?method=GetNum';

		/**
		 * @inheritDoc
		 *
		 * @throws InvalidConfigException
		 */
		public function init ()
		{
			parent::init();
			if (empty($this->ihuyi->vnsms['appid']) || empty($this->ihuyi->vnsms['apikey'])) {
				throw new InvalidConfigException('The "appid" and "apikey" properties must be set.');
			}
		}

		/**
		 * Send voice notice sms.
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
				'account'  => $this->ihuyi->vnsms['appid'],
				'password' => md5($this->ihuyi->vnsms['appid'] . $this->ihuyi->vnsms['apikey'] . $mobile . $content . $time),
				'mobile'   => $mobile,
				'content'  => $content,
				'time'     => $time,
			];

			return $this->ihuyi->httpPost(self::SEND_URL, $postData);
		}

		/**
		 * Get voice notice sms num.
		 *
		 * @return mixed
		 */
		public function getNum ()
		{
			$time     = time();
			$postData = [
				'account'  => $this->ihuyi->vnsms['appid'],
				'password' => md5($this->ihuyi->vnsms['appid'] . $this->ihuyi->vnsms['apikey'] . $time),
				'time'     => $time,
			];

			return $this->ihuyi->httpPost(self::GET_NUM_URL, $postData);
		}
	}