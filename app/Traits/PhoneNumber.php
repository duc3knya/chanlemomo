<?php
/**
 *File name : PhoneNumber.php / Date: 10/27/2021 - 11:26 PM

 */

namespace App\Traits;

class PhoneNumber
{

    //private $phonetype='CELL'; //CELL, HOME
    private $arr_Prefix = [
        'CELL' => [
            '016966' => '03966',
            '0169'   => '039',
            '0168'   => '038',
            '0167'   => '037',
            '0166'   => '036',
            '0165'   => '035',
            '0164'   => '034',
            '0163'   => '033',
            '0162'   => '032',
            '0120'   => '070',
            '0121'   => '079',
            '0122'   => '077',
            '0126'   => '076',
            '0128'   => '078',
            '0123'   => '083',
            '0124'   => '084',
            '0125'   => '085',
            '0127'   => '081',
            '0129'   => '082',
            '01992'  => '059',
            '01993'  => '059',
            '01998'  => '059',
            '01999'  => '059',
            '0186'   => '056',
            '0188'   => '058',
        ],
//        'HOME' => [
//            '076'  => '0296',
//            '064'  => '0254',
//            '0281' => '0209',
//            '0240' => '0204',
//            '0781' => '0291',
//            '0241' => '0222',
//            '075'  => '0275',
//            '056'  => '0256',
//            '0650' => '0274',
//            '0651' => '0271',
//            '062'  => '0252',
//            '0780' => '0290',
//            '0710' => '0292',
//            '026'  => '0206',
//            '0511' => '0236',
//            '0500' => '0262',
//            '0501' => '0261',
//            '0230' => '0215',
//            '061'  => '0251',
//            '067'  => '0277',
//            '059'  => '0269',
//            '0351' => '0226',
//            '04'   => '024',
//            '039'  => '0239',
//            '0320' => '0220',
//            '031'  => '0225',
//            '0711' => '0293',
//            '08'   => '028',
//            '0321' => '0221',
//            '058'  => '0258',
//            '077'  => '0297',
//            '060'  => '0260',
//            '0231' => '0213',
//            '063'  => '0263',
//            '025'  => '0205',
//            '020'  => '0214',
//            '072'  => '0272',
//            '0350' => '0228',
//            '038'  => '0238',
//            '030'  => '0229',
//            '068'  => '0259',
//            '057'  => '0257',
//            '052'  => '0232',
//            '0510' => '0235',
//            '055'  => '0255',
//            '033'  => '0203',
//            '053'  => '0233',
//            '079'  => '0299',
//            '022'  => '0212',
//            '066'  => '0276',
//            '036'  => '0227',
//            '0280' => '0208',
//            '037'  => '0237',
//            '054'  => '0234',
//            '073'  => '0273',
//            '074'  => '0294',
//            '027'  => '0207',
//            '070'  => '0270',
//            '029'  => '0216',
//        ],
    ];

    function convert($phonenumber, $convertOld = false)
    {
        if (!empty($phonenumber)) {
            //1. Xóa ký tự trắng
            $phonenumber = str_replace(' ', '', $phonenumber);
            //2. Xóa các dấu chấm phân cách
            $phonenumber = str_replace('.', '', $phonenumber);
            //3. Xóa các dấu gạch nối phân cách
            $phonenumber = str_replace('-', '', $phonenumber);
            //4. Xóa dấu mở ngoặc đơn
            $phonenumber = str_replace('(', '', $phonenumber);
            //5. Xóa dấu đóng ngoặc đơn
            $phonenumber = str_replace(')', '', $phonenumber);
            //6. Xóa dấu +
            $phonenumber = str_replace('+', '', $phonenumber);
            //7. Chuyển 84 đầu thành 0
            if (substr($phonenumber, 0, 2) == '84') {
                $phonenumber = '0'.substr($phonenumber, 2, strlen($phonenumber) - 2);
            }
            $dathaythe = false;
//            foreach ($this->arr_Prefix['HOME'] as $key => $value) {
//                //$prefixlen=strlen($key);
//                dd($key);
//                if (strpos($phonenumber, $key) === 0) {
//                    $prefix      = $key;
//                    $prefixlen   = strlen($key);
//                    $phone       = substr($phonenumber, $prefixlen, strlen($phonenumber) - $prefixlen);
//                    $prefix      = str_replace($key, $value, $prefix);
//                    $phonenumber = $prefix.$phone;
//                    dd($phonenumber);
//                    //$phonenumber=str_replace($key,$value,$phonenumber);
//                    $dathaythe = true;
//                    break;
//                }
//            }


            if ($dathaythe == false) {
                $arrayPrefix  = $convertOld ? array_flip($this->arr_Prefix['CELL']) : $this->arr_Prefix['CELL'];
                foreach ($arrayPrefix as $key => $value) {

                    //$prefixlen=strlen($key);
                    if (strpos($phonenumber, $key) === 0) {
                        $prefix      = $key;
                        $prefixlen   = strlen($key);
                        $phone       = substr($phonenumber, $prefixlen, strlen($phonenumber) - $prefixlen);
                        $prefix      = str_replace($key, $value, $prefix);
                        $phonenumber = $prefix.$phone;
                        //$phonenumber=str_replace($key,$value,$phonenumber);
                        $dathaythe = true;
                        break;
                    }
                }
            }

                    return $phonenumber;
        } else {
            return false;
        }
    }

}