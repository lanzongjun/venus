<?php
/**
 * Created by PhpStorm.
 * User: zongjun.lan
 * Date: 2020/12/18
 * Time: 10:41 AM
 */

class constant_helper
{
    /**
     * 手机指定类中的注解常量
     * @param string|object $className
     * @return array
     * @throws \ReflectionException
     * @author zongjun.lan
     */
    public static function collectClass($className)
    {
        if ($className instanceof \ReflectionClass){
            $ref = $className;
        }else{
            $ref = new \ReflectionClass($className);
        }
        $classConstants = $ref->getReflectionConstants();
        return self::getAnnotations($classConstants);
    }

    /**
     * 获取注解
     * @param array $classConstants
     * @return array
     * @author zongjun.lan
     */
    protected static function getAnnotations($classConstants)
    {
        $result = [];
        foreach ($classConstants as $classConstant) {
            $code = $classConstant->getValue();
            $docComment = $classConstant->getDocComment();
            if ($docComment) {
                $result[$code] = self::parse($docComment);
            }
        }

        return $result;
    }

    /**
     * 解析注解
     * @param string $doc
     * @return array
     * @author zongjun.lan
     */
    protected static function parse($doc)
    {
        $pattern = '/\\@(\\w+)\\(\\"(.+)\\"\\)/U';
        if (preg_match_all($pattern, $doc, $result)) {
            if (isset($result[1], $result[2])) {
                $keys = $result[1];
                $values = $result[2];

                $result = [];
                foreach ($keys as $i => $key) {
                    if (isset($values[$i])) {
                        $result[strtolower($key)] = $values[$i];
                    }
                }
                return $result;
            }
        }

        return [];
    }
}