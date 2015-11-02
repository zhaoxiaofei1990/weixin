<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function __construct() {
    }
    public function index()
    {
        //获得参数
        $nonce = $_GET['nonce'];
        $token = 'test';
        $timestamp = $_GET['timestamp'];
        $echostr = $_GET['echostr'];
        $signatrue = $_GET['signatrue'];
        //形成数组 然后按字典排序
        $array = array();
        $array = array($nonce,$timestamp,$echostr,$signatrue);
        sort($array);
        //yong sha1方式加密
        $str = sha1($array);
        if($str == $signatrue &&$echostr){
            echo $echostr;
            exit();
        }  else {
            $this->reponseMsg();
        }
    }
    //接收事件并推送
    
}