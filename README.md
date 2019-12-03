Yii2-ihuyi
==========
Yii2 ihuyi sms SDK

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist dovechen/yii2-ihuyi "*"
```

or add

```
"dovechen/yii2-ihuyi": "*"
```

to the require section of your `composer.json` file.

Configuration
-------------

To use this extension, you have to configure the Connection class in your application configuration:

```php
return [
    //....
    'components' => [
        'ihuyi' => [
            'class' => 'dovechen\yii2\ihuyi\Ihuyi',
            // normal sms.
            'sms'  => [
                'appid'  => '',
                'apikey' => '',
            ],
            // international sms.
            'isms'  => [
                'appid'  => '',
                'apikey' => '',
            ],
            // voice sms.
            'vsms'  => [
                'appid'  => '',
                'apikey' => '',
            ],
            // voice notice sms.
            'vnsms'  => [
                'appid'  => '',
                'apikey' => '',
            ],
            // marketing sms.
            'msms'  => [
                'appid'  => '',
                'apikey' => '',
            ],
            // multimedia sms.
            'mms'  => [
                'appid'  => '',
                'apikey' => '',
            ],
        ],
    ]
];
```

Usage
-----

Once the extension is installed, simply use it in your code by  :

Normal sms:
```php
// Send normal sms.
$sendResult = Yii::$app->ihuyi->sendSms('15395090543', 'Your code is 1123.');

// Get normal sms.
$smsNumInfo = Yii::$app->ihuyi->getSmsNum();

// Add normal sms template.
$smsTemplateInfo = Yii::$app->ihuyi->addSmsTemplate('Your code is【变量】');
```
International sms:
```php
// Send international sms.
$sendResult = Yii::$app->ihuyi->sendInternational('+86 15395090543', 'Your code is 1123.');

// Get international sms.
$internationalNumInfo = Yii::$app->ihuyi->getInternationalNum();
```
Voice sms:
```php
// Send voice sms.
$sendResult = Yii::$app->ihuyi->sendVoice('15395090543', '1123');

// Get voice sms.
$voiceNumInfo = Yii::$app->ihuyi->getVoiceNum();
```
Voice notice sms:
```php
// Send voice notice sms.
$sendResult = Yii::$app->ihuyi->sendVoiceNotice('15395090543', 'Your code is 1123.');

// Get voice notice sms.
$voiceNoticeNumInfo = Yii::$app->ihuyi->getVoiceNoticeNum();
```
Marketing sms:
```php
// Send marketing sms.
$sendResult = Yii::$app->ihuyi->sendMarketing('15395090543,15395090544', 'Your code is 1123.');
$sendResult = Yii::$app->ihuyi->sendMarketing(['15395090543', '15395090544'], 'Your code is 1123.');

// Get marketing sms.
$marketingNumInfo = Yii::$app->ihuyi->getMarketingNum();
```
Multimedia sms:
```php
// Send multimedia sms.
$sendResult = Yii::$app->ihuyi->sendMms('15395090543', 10048, 1001);
$sendResult = Yii::$app->ihuyi->sendMms('15395090543,15395090544', 10048, 1001);
$sendResult = Yii::$app->ihuyi->sendMms(['15395090543', '15395090544'], 10048, 1001);

// Get multimedia sms.
$multimediaNumInfo = Yii::$app->ihuyi->getMmsNum();

// Create multimedia sms.
$multimediaInfo = Yii::$app->ihuyi->createMms('New mutledia title', $zipFile);
```