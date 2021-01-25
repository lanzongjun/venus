<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2020/12/18
 * Time: 9:59 AM
 */
include_once APPPATH . 'helpers/constant_helper.php';
/**
 * 常量Trait
 * Class ConstantTrait
 * @package common\bases\traits
 */
trait ConstantTrait
{
    /**
     * @param string $name
     * @param array|null $arguments
     * @return mixed
     * @throws null
     * @author likexin
     */
    public static function __callStatic($name, $arguments)
    {
        if (empty($arguments)) {
            throw new Exception('The Code is required');
        }

        $code = $arguments[0];
        $name = strtolower(substr($name, 3));

        /**
         * 正常应该是@Message()，由于Exception::getMessage()冲突，此处适配下
         * @author likexin
         */
        if ($name == 'messages') {
            $name = 'message';
        }

//        $messages       = Yii::$app->cache->get(static::class);
//        $filemtimeCache = Yii::$app->cache->get(static::class . "_filemtime");

        $ref       = new \ReflectionClass(static::class);
        $filemtime = filemtime($ref->getFileName());

        $messages = self::getAll($ref);

//        if (empty($messages) || $filemtime > $filemtimeCache) {
//            $messages = self::getAll($ref);
//            Yii::$app->cache->set(static::class, $messages, 3600);
//            Yii::$app->cache->set(static::class . "_filemtime", $filemtime);
//        }


        return $messages[$code][$name];
    }

    /**
     * 获取全部
     * @param null $class
     * @return array
     * @throws \ReflectionException
     * @author zongjun.lan
     */
    public static function getAll($class = null)
    {
        $class = isset($class) ? $class : get_called_class();
        return (array)constant_helper::collectClass($class);
    }
}