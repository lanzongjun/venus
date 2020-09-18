<?php

class BaseModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public static function unitMap($unit)
    {
        switch ($unit)
        {
            case '1':
                $unitMap = '个';
                break;
            case '2':
                $unitMap = '份';
                break;
            case '3':
                $unitMap = '斤';
                break;
            default:
                $unitMap = '未知';

        }

        return $unitMap;
    }
}