<?php

	namespace dovechen\yii2\ihuyi\src\gateways;

	use dovechen\yii2\ihuyi\components\IhuyiComponent;
	use yii\base\InvalidConfigException;
	use yii\base\InvalidParamException;

	/**
	 * Class MarketingGateway
	 * @package dovechen\yii2\ihuyi\src\gateways
	 */
	class MarketingGateway extends IhuyiComponent
	{
		const SEND_URL = 'http://api.yx.ihuyi.com/webservice/sms.php?method=Submit';
		const GET_NUM_URL = 'http://api.yx.ihuyi.com/webservice/sms.php?method=GetNum';

		/**
		 * @inheritDoc
		 *
		 * @throws InvalidConfigException
		 */
		public function init ()
		{
			parent::init();
			if (empty($this->ihuyi->msms['appid']) || empty($this->ihuyi->msms['apikey'])) {
				throw new InvalidConfigException('The "appid" and "apikey" properties must be set.');
			}
		}

		/**
		 * Send marketing sms.
		 *
		 * @param      $mobile
		 * @param      $content
		 * @param null $stime
		 *
		 * @return mixed
		 */
		public function send ($mobile, $content, $stime = NULL)
		{
			if (empty($mobile) || empty($content)) {
				throw new InvalidParamException('The "mobile" and "content" propoerties must be set.');
			}

			if (is_string($mobile)) {
				$mobile = explode(',', $mobile);
			}

			if (count($mobile) < 2) {
				throw new InvalidParamException('The send to people must be than one.');
			}

			$mobile = implode(',', $mobile);

			$time     = time();
			$postData = [
				'account'  => $this->ihuyi->msms['appid'],
				'password' => md5($this->ihuyi->msms['appid'] . $this->ihuyi->msms['apikey'] . $mobile . $content . $time),
				'mobile'   => $mobile,
				'content'  => $content,
				'time'     => $time,
			];
			if (!empty($stime)) {
				$postData['stime'] = $stime;
			}

			return $this->ihuyi->httpPost(self::SEND_URL, $postData);
		}

		/**
		 * Get maketing sms num.
		 *
		 * @return mixed
		 */
		public function getNum ()
		{
			$time     = time();
			$postData = [
				'account'  => $this->ihuyi->msms['appid'],
				'password' => md5($this->ihuyi->msms['appid'] . $this->ihuyi->msms['apikey'] . $time),
				'time'     => $time,
			];

			return $this->ihuyi->httpPost(self::GET_NUM_URL, $postData);
		}
	}