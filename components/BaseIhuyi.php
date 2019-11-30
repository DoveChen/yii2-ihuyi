<?php

	namespace dovechen\yii2\ihuyi\components;

	use yii\base\Component;

	/**
	 * Class BaseIhuyi
	 * @package dovechen\yii2\ihuyi\component
	 */
	abstract class BaseIhuyi extends Component
	{

		/**
		 * 返回错误码
		 * @var array
		 */
		public $lastError;

		/**
		 * Api url
		 *
		 * @param       $url
		 * @param array $options
		 *
		 * @return string
		 */
		protected function httpBuildQuery ($url, array $options)
		{
			if (!empty($options)) {
				$url .= (stripos($url, '?') === NULL ? '&' : '?') . http_build_query($options);
			}

			return $url;
		}

		/**
		 * Http Get method
		 *
		 * @param       $url
		 * @param array $options
		 *
		 * @return mixed
		 */
		public function httpGet ($url, array $options = [])
		{
			\Yii::info([
				'url'     => $url,
				'options' => $options
			], __METHOD__);

			return $this->parseHttpRequest(function ($url) {
				return $this->http($url);
			}, $this->httpBuildQuery($url, $options));
		}

		/**
		 * Http Post method
		 *
		 * @param       $url
		 * @param array $postOptions
		 * @param array $options
		 *
		 * @return mixed
		 */
		public function httpPost ($url, array $postOptions, array $options = [])
		{
			\Yii::info([
				'url'         => $url,
				'postOptions' => $postOptions,
				'options'     => $options
			], __METHOD__);

			return $this->parseHttpRequest(function ($url, $postOptions) {
				return $this->http($url, [
					CURLOPT_POST       => true,
					CURLOPT_POSTFIELDS => $postOptions
				]);
			}, $this->httpBuildQuery($url, $options), $postOptions);
		}

		/**
		 * Http Raw data Post method
		 *
		 * @param       $url
		 * @param       $postOptions
		 * @param array $options
		 *
		 * @return mixed
		 */
		public function httpRaw ($url, $postOptions, array $options = [])
		{
			\Yii::info([
				'url'         => $url,
				'postOptions' => $postOptions,
				'options'     => $options
			], __METHOD__);

			return $this->parseHttpRequest(function ($type, $url, $postOptions) {
				return $this->http($url, [
					CURLOPT_POST       => true,
					CURLOPT_POSTFIELDS => is_array($postOptions) ? json_encode($postOptions, JSON_UNESCAPED_UNICODE) : $postOptions
				]);
			}, $this->httpBuildQuery($url, $options), $postOptions);
		}

		/**
		 * @param callable          $callable    Http main function
		 * @param int               $type        Api type
		 * @param string            $url         Api url
		 * @param array|string|null $postOptions Api post options
		 *
		 * @return array|bool
		 */
		abstract public function parseHttpRequest (callable $callable, $url, $postOptions = NULL);

		/**
		 * Curl
		 *
		 * @param       $url
		 * @param array $options
		 *
		 * @return bool|mixed
		 */
		protected function http ($url, $options = [])
		{
			$options = [
					CURLOPT_URL            => $url,
					CURLOPT_TIMEOUT        => 30,
					CURLOPT_CONNECTTIMEOUT => 30,
					CURLOPT_RETURNTRANSFER => true,
				] + (stripos($url, "https://") !== false ? [
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_SSL_VERIFYHOST => false,
					CURLOPT_SSLVERSION     => CURL_SSLVERSION_TLSv1
				] : []) + $options;

			$curl = curl_init();
			curl_setopt_array($curl, $options);
			$content = curl_exec($curl);
			$status  = curl_getinfo($curl);
			curl_close($curl);
			if (isset($status['http_code']) && $status['http_code'] == 200) {
				return $content ?: false;
			}

			\Yii::error([
				'result' => $content,
				'status' => $status
			], __METHOD__);

			return false;
		}

		/**
		 * @param $xml
		 *
		 * @return mixed
		 */
		public function xmlToArray ($xml)
		{
			$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
			if (preg_match_all($reg, $xml, $matches)) {
				$count = count($matches[0]);
				for ($i = 0; $i < $count; $i++) {
					$subxml = $matches[2][$i];
					$key    = $matches[1][$i];
					if (preg_match($reg, $subxml)) {
						$arr[$key] = $this->xmlToArray($subxml);
					} else {
						$arr[$key] = $subxml;
					}
				}
			}

			return $arr;
		}
	}