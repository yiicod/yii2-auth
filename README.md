Auth extensions
===============

If you want simple auth (login/signup/forgot), this is what you want! This extension
has simple action what have added in controller. Extension has events: 

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yiicod/yii2-auth "*"
```

or add

```json
"yiicod/yii2-auth": "*"
```

to the require section of your composer.json.

run
```php
php yii migrate/up --migrationPath=@yiicod/auth/migrations
```
Please note that messages are wrapped with ```Yii::t()``` to support message translations, you should define default message source for them if you don't use i18n.
```php
'i18n' => [
    'translations' => [
        '*' => [
            'class' => 'yii\i18n\PhpMessageSource'
        ],
    ],
],
```
Config
------

```php
'components' => [
    'auth' => [
        'class' => 'yiicod\auth\Auth',
    ],
]

'bootstrap' => ['auth']
```

Using
-----

Copy yiicod\auth\controllers\WebUserController to controllers.
