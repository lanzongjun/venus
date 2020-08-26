<?php
/**
 * Description of DecodeEle
 *
 * @author Vincent
 */
class DecodeEle {

    private $a_ele_num_mapping = array(
        'C314' => '0',
        'E2DE' => '1',
        'CBF5' => '2',
        'CAB8' => '3',
        'CC43' => '4',
        'E59C' => '5',
        'B84D' => '6',
        'CB12' => '7',
        'E2F6' => '8',
        'CE2B' => '9',
        'EFE8' => '.',
        'BAF2' => ','
    );

    public function decode($s_content) {
        $s_hex = $this->unicode_encode($s_content);
        $i_count = strlen($s_hex);
        $s_substr = '';
        $s_result = '';
        for ($i = 0; $i < $i_count; $i = $i + 4) {
            $s_substr = substr($s_hex, $i, 4);
            $s_result .= $this->_decode($s_substr);
        }
        return $s_result;
    }

    private function unicode_encode($strLong) {
        $strArr = preg_split('/(?<!^)(?!$)/u', $strLong); //拆分字符串为数组(含中文字符)
        $resUnicode = '';
        foreach ($strArr as $str) {
            $bin_str = '';
            $arr = is_array($str) ? $str : str_split($str); //获取字符内部数组表示,此时$arr应类似array(228, 189, 160)
            foreach ($arr as $value) {
                $bin_str .= decbin(ord($value)); //转成数字再转成二进制字符串,$bin_str应类似111001001011110110100000,如果是汉字"你"
            }
            $bin_str = preg_replace('/^.{4}(.{4}).{2}(.{6}).{2}(.{6})$/', '$1$2$3', $bin_str); //正则截取, $bin_str应类似0100111101100000,如果是汉字"你"
            $unicode = dechex(bindec($bin_str)); //返回unicode十六进制
            $_sup = '';
            for ($i = 0; $i < 4 - strlen($unicode); $i++) {
                $_sup .= '0'; //补位高字节 0
            }
            $str = $_sup . $unicode; //不加 \u  返回
            $resUnicode .= $str;
        }
        return $resUnicode;
    }

    private function _decode($s_content) {
	$s_content = strtoupper($s_content);
        if (array_key_exists($s_content,$this->a_ele_num_mapping)) {
            return $this->a_ele_num_mapping[$s_content];
        } else {
            return '?';
        }
    }

}
