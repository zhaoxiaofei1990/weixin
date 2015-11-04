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

    public function reponseMsg() {
        //获取微信的值
        $postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
        $postObj = simplexml_load_string($postArr);
        //判断该数据包是否是订阅的事件推送
        if (strtolower($postObj->MsgType) == 'event') {
            //如果是关注 subscribe 事件
            if (strtolower($postObj->Event == 'subscribe')) {
                //回复用户消息(纯文本格式)	
                $toUser = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time = time();
                $msgType = 'text';
                $content = '欢迎关注我们的微信公众账号' . $postObj->FromUserName . '-' . $postObj->ToUserName;
                $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml>";
                $info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info;
            }
        }
        //客服请求微信   微信请求服务器公众平台
        if(strtolower($postObj->MsgType)=='text'){
            switch (trim($postObj->Content)){
                case 1:
                    $content='1';
                break;
                case 2:
                    $content = '2';
                break;
                case 3:
                    $content = 99;
                break;
                case 4:
                    $content = 66;
                break;
            }
            $template = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <Content><![CDATA[%s]]></Content>
                </xml>";
            $fromUser = $postObj->ToUserName;
            $toUser =$postObj->FromUserName;
            $time = time();
            $msgType ='text';
            echo sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
        }
    }

}
