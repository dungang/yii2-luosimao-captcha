<?php
/**
 * Author: dungang
 * Date: 2017/4/12
 * Time: 14:27
 */

namespace dungang\luosimao;


use yii\bootstrap\InputWidget;
use yii\helpers\Html;
use yii\web\JsExpression;

class CaptchaWidget extends InputWidget
{
    /**
     * @var bool 是否需要重置验证码按钮
     */
    public $reset = false;

    /**
     * app site key
     * @var string
     */
    public $siteKey;

    /**
     * @var integer 验证码的宽度
     */
    public $width = 400;

    /**
     * @var string 出来相应的回调函数
     */
    public $callback;

    public function run()
    {
        CaptchaAsset::register($this->view);

        if (empty($this->siteKey)) {
            if (isset(\Yii::$app->params['luosimao']) &&
                isset(\Yii::$app->params['luosimao']['siteKey'])) {
                $this->siteKey = \Yii::$app->params['luosimao']['siteKey'];
            }
        }
        $options = [
            'data-site-key'=>$this->siteKey,
            'data-width'=>$this->width,
            'class'=>'l-captcha'
        ];
        if ($this->callback) {
            $options['data-callback'] = $this->callback;
        }
        if ($this->hasModel()) {
            $attr = $this->attribute;
            $this->model->$attr = 'captcha';
            $input = Html::activeHiddenInput($this->model,$this->attribute,$this->options);
        } else {
            $input = Html::hiddenInput($this->name,'captcha',$this->options);
        }
        $captcha = $input . Html::tag('div','',$options);

        if ($this->reset) {
            $captcha .= Html::a(\Yii::t('app','Rest Captcha'),'#',[
                'onclick' => new JsExpression('LUOCAPTCHA.reset()')
            ]);
        }
        return $captcha;
    }
}