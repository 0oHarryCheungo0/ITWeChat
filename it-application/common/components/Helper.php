<?php
/**
 * 全局帮助函数类
 */
namespace common\components;

use Picqer\Barcode\BarcodeGeneratorPNG as Barcode;
use yii\base\Component;

class Helper extends Component
{
    public function barcode($string)
    {
        $generator = new Barcode();
        return base64_encode($generator->getBarcode($string, $generator::TYPE_CODE_128, 2, 40));
    }

}
