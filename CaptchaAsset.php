<?php
/**
 * Author: dungang
 * Date: 2017/4/12
 * Time: 14:26
 */

namespace dungang\luosimao;


use yii\web\AssetBundle;

class CaptchaAsset extends AssetBundle
{

    public $baseUrl = '//captcha.luosimao.com/static/js/';

    public $js = ['api.js'];

}