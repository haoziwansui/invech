<?php
return [
        'app\events\Test' => [
            'app\listeners\TestListener@onAction',
        ],
        'app\events\LotteryEvent' => [
            'app\listeners\LotteryListener@onAction',
        ],
        'app\events\SmsEvent' => [
            'app\listeners\SmsListener@onAction',
        ],
        'user.login' => [
        	'app\listeners\UserListener@onLogin',
        ],
        'trend.cache' => [
            'app\listeners\TrendListener@onAction',
        ],        
    ];


