<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/7/3
 * Time: 下午4:17
 */

namespace common\lib;

use Picqer\Barcode\BarcodeGeneratorPNG as Generator;
use dosamigos\qrcode\QrCode;

class Barcode
{
    public static function base64string($string)
    {
        $generator = new Generator();
        return base64_encode($generator->getBarcode($string, $generator::TYPE_CODE_128, 2, 50));
    }

    public static function qrcode($string){
        return QrCode::raw($string);
    }

}