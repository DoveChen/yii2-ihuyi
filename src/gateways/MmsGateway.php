<?php

	namespace dovechen\yii2\ihuyi\src\gateways;

	use dovechen\yii2\ihuyi\components\IhuyiComponent;
	use yii\base\InvalidConfigException;
	use yii\base\InvalidParamException;

	/**
	 * Class MmsGateway
	 * @package dovechen\yii2\ihuyi\src\gateways
	 */
	class MmsGateway extends IhuyiComponent
	{
		const SEND_URL = 'http://10658.cc/webservice/api?method=SendMms';
		const GET_NUM_URL = 'http://10658.cc/webservice/api?method= GetNum';
		const CREATE_MMS_URL = 'http://10658.cc/webservice/api?method=CreateMms';

		/**
		 * @inheritDoc
		 *
		 * @throws InvalidConfigException
		 */
		public function init ()
		{
			parent::init();
			if (empty($this->ihuyi->mms['appid']) || empty($this->ihuyi->mms['apikey'])) {
				throw new InvalidConfigException('The "appid" and "apikey" properties must be set.');
			}
		}

		/**
		 * Send multimedia sms.
		 *
		 * @param      $mobile
		 * @param      $mmsid
		 * @param      $pid
		 * @param null $time
		 *
		 * @return mixed
		 */
		public function send ($mobile, $mmsid, $pid, $time = NULL)
		{
			if (empty($mobile) || empty($mmsid) || empty($pid)) {
				throw new InvalidParamException('The "mobile" and "mmsid" and "product id" propoerties must be set.');
			}

			if (is_array($mobile)) {
				$mobile = implode(',', $mobile);
			}

			$postData = [
				'account'  => $this->ihuyi->mms['appid'],
				'password' => $this->ihuyi->mms['apikey'],
				'mobile'   => $mobile,
				'mmsid'    => $mmsid,
				'pid'      => $pid,
			];
			if (!empty($time)) {
				$postData['time'] = $time;
			}

			return $this->ihuyi->httpPost(self::SEND_URL, $postData);
		}

		/**
		 * Get multimedia sms num.
		 *
		 * @return mixed
		 */
		public function getNum ()
		{
			$postData = [
				'account'  => $this->ihuyi->mms['appid'],
				'password' => $this->ihuyi->mms['apikey'],
			];

			return $this->ihuyi->httpPost(self::GET_NUM_URL, $postData);
		}

		/**
		 * Create multimedia sms.
		 *
		 * @param $title
		 * @param $zipFile
		 *
		 * @return mixed
		 */
		public function create ($title, $zipFile)
		{
			$postData = [
				'account'  => $this->ihuyi->mms['appid'],
				'password' => $this->ihuyi->mms['apikey'],
				'title'    => $title,
				'zipfile'  => $zipFile,
			];

			return $this->ihuyi->httpPost(self::CREATE_MMS_URL, $postData);
		}
	}