<?php

	namespace dovechen\yii2\ihuyi\src\gateways;

	use dovechen\yii2\ihuyi\components\IhuyiComponent;
	use yii\base\InvalidConfigException;
	use yii\base\InvalidParamException;

	/**
	 * Class InternationalGateway
	 * @package dovechen\yii2\ihuyi\src\gateways
	 */
	class InternationalGateway extends IhuyiComponent
	{
		const SEND_URL = 'http://api.isms.ihuyi.com/webservice/isms.php?method=Submit';
		const GET_NUM_URL = 'http://api.isms.ihuyi.com/webservice/isms.php?method=GetNum';

		/**
		 * @inheritDoc
		 *
		 * @throws InvalidConfigException
		 */
		public function init ()
		{
			parent::init();
			if (empty($this->ihuyi->isms['appid']) || empty($this->ihuyi->isms['apikey'])) {
				throw new InvalidConfigException('The "appid" and "apikey" properties must be set.');
			}
		}

		/**
		 * Send international sms.
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
				'account'  => $this->ihuyi->isms['appid'],
				'password' => md5($this->ihuyi->isms['appid'] . $this->ihuyi->isms['apikey'] . $mobile . $content . $time),
				'mobile'   => $mobile,
				'content'  => $content,
				'time'     => $time,
			];

			return $this->ihuyi->httpPost(self::SEND_URL, $postData);
		}

		/**
		 * Get international sms num.
		 *
		 * @return mixed
		 */
		public function getNum ()
		{
			$time     = time();
			$postData = [
				'account'  => $this->ihuyi->isms['appid'],
				'password' => md5($this->ihuyi->isms['appid'] . $this->ihuyi->isms['apikey'] . $time),
				'time'     => $time,
			];

			return $this->ihuyi->httpPost(self::GET_NUM_URL, $postData);
		}
	}