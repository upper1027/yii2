<?php

namespace frontend\controllers;

use app\models\Merge;
use Yii;
use yii\redis\Connection;
use frontend\models\User;

/**
 * Site controller
 */
class TestController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionTest()
    {
        $data = User::find()->where(['>', 'id', 58])->all();
        /** @var Connection $redis */
        $redis = Yii::$app->redis;
        $redis->executeCommand('set', ['key1', 'value1', 'nx', 'ex' , 10]);

        return $this->success($data);
    }

    public function actionEs()
    {
        $merge = new Merge();
        $query = $merge::find();
        $queryData = [
            'bool' => [
                "must" => [
                    [
                        "range" => [
                            "age" => [
                                "gt" => 10,
                                "lt" => 100,
                            ],
                        ],
                    ],
                    [
                        "multi_match" => [
                            "query" => "顾村",
                            "fields" => [
                                "name",
                                "address",
                            ],
                        ]
                    ]
                ],
            ],
        ];

        $data = $query->query($queryData)->offset(0)->limit(10)->all();

        return $this->success($data);
    }
}
