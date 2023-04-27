<?php

namespace common\models;

use yii\elasticsearch\ActiveRecord;
use yii\data\Pagination;

class Elasticsearch extends ActiveRecord
{

    /**
     * 使用的数据库连接
     */
    public static function getDb(){
        return \Yii::$app->get('elasticsearch');
    }

    public function mapping(): array
    {
        return [];
    }

    /**
     * 获取所有索引映射
     */
    public function getMapping()
    {
        return self::getDb()->createCommand()->getMapping();
    }

    /**
     * 根据索引主键获取相关信息
     * 支持单个或多个
     */
    public function getByKey($id){
        if(is_array($id)){
            $res = self::mget($id);
        }else{
            $res = self::get($id);
        }
        return $res;
    }

    /**
     * 单个
     * 默认返回object对象 返回数组 添加->asArray()
     */
    public function getOne($query = []){
        $es_query = self::find();

        // 匹配查询
        if($query && !empty($query)){
            $es_query->query($query);
        }
        // 分组
        $res = $es_query->one();

        return $res;
    }

    /**
     * 列表
     * 默认返回object对象 返回数组 添加->asArray()
     * search 与 all 区别在于 all是在search基础上处理再拿出结果
     */
    public function getList($query = [], $order = [], $offset = 0, $limit = 20){
        $es_query = self::find();

        // 匹配查询
        if($query && !empty($query)){
            $es_query->query($query);
        }
        // 排序
        if($order && !empty($order)){
            $es_query->orderby($order);
        }
        // 分组
        $res = $es_query->offset($offset)->limit($limit)->asArray()->all();
        $list = array_column($res, '_source');

        return $list;
    }

    /**
     * 分页列表
     * 默认返回object对象 返回数组 添加->asArray()
     * search 与 all 区别在于 all是在search基础上处理再拿出结果
     */
    public function getPageList($query = [], $order = []){
        $esQuery = self::find();

        // 匹配查询
        if($query && !empty($query)){
            $esQuery->query($query);
        }
        // 排序
        if($order && !empty($order)){
            $esQuery->orderby($order);
        }

        // 分组
        $count = $esQuery->search();
        // 分页
        $pages = new Pagination(['totalCount' => $count['hits']['total']]);
        // 分组
        $res = $esQuery->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        $list = array_column($res, '_source');

        return ['list' => $list, 'pages' => $pages];
    }

    /**
     * 列表
     * 默认返回object对象 返回数组 添加->asArray()
     * search 与 all 区别在于 all是在search基础上处理再拿出结果
     */
    public function getFilterList($filter_arr = [], $query = [], $order = [], $offset = 0, $limit = 20){
        $es_query = self::find();

        // 过滤器
        if($filter_arr && !empty($filter_arr)){
            $es_query->postFilter($filter_arr);
        }
        // 匹配查询
        if($query && !empty($query)){
            $es_query->query($query);
        }
        // 排序
        if($order && !empty($order)){
            $es_query->orderby($order);
        }
        // 分组
        $res = $es_query->offset($offset)->limit($limit)->all();

        return $res;
    }

    /**
     * 获取高亮列表
     * 默认返回object对象 返回数组 添加->asArray()
     * search 与 all 区别在于 all是在search基础上处理再拿出结果
     * 循环使用$v->highlight获取高亮列表
     */
    public function getHighLightList($highlight_arr = [], $query = [], $offset = 0, $limit = 20){
        $es_query = self::find();

        // 高亮
        if($highlight_arr && !empty($highlight_arr)){
            $es_query->highlight($highlight_arr);
        }
        // 匹配查询
        if($query && !empty($query)){
            $es_query->query($query);
        }
        // 分组
        $res = $es_query->offset($offset)->limit($limit)->all();

        return $res;
    }

    /**
     * 获取聚合列表
     * 默认返回object对象 返回数组 添加->asArray()
     * search 与 all 区别在于 all是在search基础上处理再拿出结果
     */
    public function getAggList($aggregate_name, $addAggregate_arr = [], $query = [], $offset = 0, $limit = 20){
        $es_query = self::find();

        // 聚合
        if($addAggregate_arr && !empty($addAggregate_arr)){
            $es_query->addAggregate($aggregate_name, $addAggregate_arr);
        }
        // 匹配查询
        if($query && !empty($query)){
            $es_query->query($query);
        }
        // 分组
        $res = $es_query->offset($offset)->limit($limit)->search();

        return ['list' => $res['hits']['hits'], $aggregate_name => $res['aggregations'][$aggregate_name]];
    }
}