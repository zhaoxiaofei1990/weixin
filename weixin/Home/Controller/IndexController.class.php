<?php

namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller {

    public function index() {
        $nonce = $_GET['nonce'];
        $token = 'imooc';
        $timestamp = $_GET['timestamp'];
        $echostr = $_GET['echostr'];
        $signature = $_GET['signature'];
        //形成数组，然后按字典序排序
        $array = array();
        $array = array($nonce, $timestamp, $token);
        sort($array);
        //拼接成字符串,sha1加密 ，然后与signature进行校验
        $str = sha1(implode($array));
        if ($str == $signature && $echostr) {
            //第一次接入weixin api接口的时候
            echo $echostr;
            exit;
        } else {
            $this->reponseMsg();
        }
    }
    public function reponseMsg(){
        //获取微信的值
        $postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
        $postObj = simplexml_load_string($postArr);
        //判断该包是否是订阅推送事件
        if(strtolower($postObj->msgType=='event')){
            if(strtolower($postObj->Event)=='subscribe'){
             //组装数据包
                $toUser = $postObj->FormUserName;
                $fromUser = $postObj->TouUerName;
                $time = time();
                $msgType='text';
                $content = '欢迎你,亲'.$postObj->FormUserName.'-'.$postObj->ToUserName;
                $tmp = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Event><![CDATA[%s]]></Event>
                        </xml>";
                $info = srintf($tmp,$touer,$formUser,$time,$msgType,$content);
                echo $info;
                
            }
        }
    }

}
