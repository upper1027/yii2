<?php

namespace app\models;

use common\models\Elasticsearch;

class Merge extends Elasticsearch
{
    public function attributes(): array
    {
        return ['name', 'age', 'address'];
    }

    public static function index(): string
    {
        return 'test';
    }

    public static function type(): string
    {
        return 'test';
    }

    public static function getDb()
    {
        return \Yii::$app->get('elasticsearch');
    }

    public function getMapping()
    {
        $index = self::index();
        return self::getDb()->createCommand()->getMapping($index);
    }
}