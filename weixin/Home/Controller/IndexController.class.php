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
    public function reponseMse(){
        //接收
        $postAtt = $GLOBALS('HTTP_RAW_POST_DATA ');
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


















