<?php
/**
 * Author: dungang
 * Date: 2017/4/12
 * Time: 14:52
 */

namespace dungang\luosimao;


use yii\helpers\Json;
use yii\validators\Validator;

class CaptchaValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if (empty(\Yii::$app->params['luosimao']) ||
            empty(\Yii::$app->params['luosimao']['apiKey'])) {
            return $this->addError($model,$attribute,\Yii::t('app','Lost luosimao captcha config'));
        }
        $apiKey = \Yii::$app->params['luosimao']['apiKey'];
        if($response = \Yii::$app->request->post('luotest_response')) {

            $rst =  $this->request('https://captcha.luosimao.com/api/site_verify',true,[
                'api_key'=>$apiKey,
                'response'=>$response
            ]);

            if ($rst) {
                $rst = Json::decode($rst);
                if ($rst['res'] != 'success') {
                    return $this->addError($model,$attribute,$rst['msg']);
                }
            }
        }

        return $this->addError($model,$attribute,\Yii::t('app','Server Error'));

    }


    /**
     * @param $url
     * @param $isPost
     * @param array $data
     * @param array $options
     * @param array $headers
     * @return mixed
     * @throws \Exception
     */
    public function request($url,$isPost,$data=[],$options=[],$headers=[])
    {
        $ch = curl_init();
        //set url
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_TIMEOUT,$this->timeout);
        //定义options
        if (is_array($options)) {
            foreach ($options as $const => $option) {
                if (defined($const)) {
                    $const_val = constant($const);
                    switch ($const_val) {
                        case CURLOPT_HTTPHEADER:
                        case CURLOPT_POST:
                        case CURLOPT_POSTFIELDS:
                        case CURLOPT_URL:
                            continue;
                        default:
                            curl_setopt($ch,$const_val,$option);
                    }
                }
            }
        }
        //定义头部信息
        if (is_array($headers)) {
            curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        }

        //定义post
        if ($isPost) {
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        }

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new \Exception($response, $httpStatusCode);
            }
        }
        curl_close($ch);
        return $response;

    }
}