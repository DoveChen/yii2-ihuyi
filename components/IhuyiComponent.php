<?php

	namespace dovechen\yii2\ihuyi\components;

	use yii\base\BaseObject;

	/**
	 * Class IhuyiComponent
	 * @package dovechen\yii2\ihuyi\component
	 */
	class IhuyiComponent extends BaseObject
	{
		/**
		 * @var BaseIhuyi $ihuyi
		 */
		protected $ihuyi;

		/**
		 * IhuyiComponent constructor.
		 *
		 * @param BaseIhuyi $ihuyi
		 * @param array     $config
		 */
		public function __construct (BaseIhuyi $ihuyi, $config = [])
		{

			$this->ihuyi = $ihuyi;
			parent::__construct($config);
		}

	}