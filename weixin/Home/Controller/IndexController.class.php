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
   	//获得参数 signature nonce token timestamp echostr
        $nonce     = $_GET['nonce'];
        $token     = 'imooc';
        $timestamp = $_GET['timestamp'];
        $echostr   = $_GET['echostr'];
        $signature = $_GET['signature'];
        //形成数组，然后按字典序排序
        $array = array();
        $array = array($nonce, $timestamp, $token);
        sort($array);
        //拼接成字符串,sha1加密 ，然后与signature进行校验
        $str = sha1( implode( $array ) );
        if( $str  == $signature && $echostr ){
                //第一次接入weixin api接口的时候
                echo  $echostr;
                exit;
        }else{
                $this->reponseMsg();
        }
}
    //接收事件并推送
    public function reponseMse(){
        //接收
        $postArr = $GLOBALS('HTTP_RAW_POST_DATA');
        //处理消息类型 并设置回复类型和内容
        $postAbj = simplexml_load_string($postArr);
//                <xml>
//        <ToUserName><![CDATA[toUser]]></ToUserName>
//        <FromUserName><![CDATA[FromUser]]></FromUserName>
//        <CreateTime>123456789</CreateTime>
//        <MsgType><![CDATA[event]]></MsgType>
//        <Event><![CDATA[subscribe]]></Event>
//        </xml>
        $postAbj->ToUserName=''; 
        $postAbj->FromUserName='';
        $postAbj->CreateTime='';
        $postAbj->MsgType='';    
        $postAbj->Event='';
        if(strtolower($postAbj->MsgType)=='event'){
            if(strtolower($postAbj->Event=='subscribe')){
                //回复用户消息
                $toUser = $postAbj->FormUserName;
                $formUser = $postAbj->toUserName;
                $time = $time();
                $Msgtype='text';
                $Content='欢迎你，亲';
                $template = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <Event><![CDATA[%s]]></Event>
                </xml>";
                $info = spintf($toUser,$formUser,$time,$Msgtype,$Content);
                    echo $info;
                }
        }

    }
}


















