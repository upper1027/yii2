<?php

namespace frontend\controllers;
header("Access-Control-Allow-Origin: *");
//如果需要设置允许所有域名发起的跨域请求，可以使用通配符 * ，如果限制自己的域名的话写自己的域名就行了。
// 响应类型 *代表通配符，可以指出POST,GET等固定类型
header('Access-Control-Allow-Methods:* ');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header('Access-Control-Allow-Credentials: true');

use yii\web\Controller;
use yii\web\Response;

class BaseController extends Controller
{
    public function init(){
        $this->enableCsrfValidation = false;
    }

    public function success(array $data = []): Response
    {
        $result['code'] = 200;
        $result['result'] = [
            'msg' => 'success',
            'data' => $data,
        ];
        $response = \Yii::$app->response;
        $response->data = $result;
        $response->format = Response::FORMAT_JSON;
        return $response;
    }

    public function error(array $data = []): Response
    {
        $result['code'] = 500;
        $result['result'] = [
            'msg' => 'error',
            'data' => $data,
        ];
        $response = \Yii::$app->response;
        $response->data = $result;
        $response->format = Response::FORMAT_JSON;
        return $response;
    }

}