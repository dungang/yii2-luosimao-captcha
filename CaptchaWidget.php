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
    public $siteKey = 'aab758dfe65d67a418589e950ea07b05';

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
        $options = [
            'data-site-key'=>$this->siteKey,
            'data-width'=>$this->width
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
        $captcha = $input
        . '<input type="hidden" id="lc-captcha-response" name="luotest_response" value="">'
        . Html::tag('div','',$options);

        if ($this->reset) {
            $captcha .= Html::a(\Yii::t('app','Rest Captcha'),'#',[
                'onclick' => new JsExpression('LUOCAPTCHA.reset()')
            ]);
        }
        return $captcha;
    }
}