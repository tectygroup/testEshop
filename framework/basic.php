<?php
/**
 * Created by PhpStorm.
 * User: qycit
 * Date: 2016/7/17
 * Time: 12:25
 */

namespace framework\basic;
class basic {
    function __get($name)
    {
        // TODO: Implement __get() method.
        $getter = 'get' . $name;              // getter函数的函数名
        if (method_exists($this, $getter)) {
            return $this->$getter();          // 调用了getter函数
        } elseif (method_exists($this, 'set' . $name)) {
            throw new InvalidCallException('Getting write-only property: '
                . get_class($this) . '::' . $name);
        } else {
            throw new UnknownPropertyException('Getting unknown property: '
                . get_class($this) . '::' . $name);
        }
    }
    function __set($name,$value){
        $setter = 'set' . $name;             // setter函数的函数名
        if (method_exists($this, $setter)) {
            $this->$setter($value);          // 调用setter函数
        } elseif (method_exists($this, 'get' . $name)) {
            throw new InvalidCallException('Setting read-only property: ' .
                get_class($this) . '::' . $name);
        } else {
            throw new UnknownPropertyException('Setting unknown property: '
                . get_class($this) . '::' . $name);
        }

    }
}
class singleMode extends basic{
    //this class is to store the item of themselves
    private static  $_self;

    private function __construct(){

    }
    public function __clone(){
        //clone is forbidden
        trigger_error('Clone is not allow!',E_USER_ERROR);
    }

    /**
     *
     */
    public static function getSingle(){
        //this function is to get this single item;
        if (!self::$_self instanceof self){
            self::$_self=new self();
        }
        return self::$_self;
    }

}
class page extends singleMode{
   public function __construct()
   {
       echo "this is a page";
   }
}
class database extends singleMode{
    public function __construct()
    {
        echo "this is a database connector";

    }
}
$page =page::getSingle();
$database= database::getSingle();
