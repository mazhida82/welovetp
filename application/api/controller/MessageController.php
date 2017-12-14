<?php

namespace app\api\controller;


use app\api\model\Message;
use think\Request;

class MessageController extends BaseController
{
    /**
     * 我的消息列表
     * @param Request $request
     * @return \think\response\Json
     */
    public function mlist(Request $request){
        $data = $request->param();
        $rule=['username'=>'require'];
        $res = $this->validate($data,$rule);
        if ($res !== true) {
            return json(['code' => __LINE__, 'msg' => '用户名传值错误']);
        }
        return json(Message::getList($data['username']));
    }

    /**
     * 查看消息详情页
     * @param Request $request
     * @return \think\response\Json
     */
    public function info(Request $request){
        $data = $request -> param();
        $rule = ['username'=>'require','message_id'=>'require|number'];
        $res = $this->validate($data, $rule);
        if($res !== true){
            return json(['code'=>__LINE__,'msg'=>'传值错误']);
        }
        return json(Message::getInfo($data));
    }
//   /* /*
//    *  select message from user and shop
//    * */
//    public function index(Request $request){
//        $data = $request->get();
//        $rule=[
//            'username'=>'require','shop_id'=>'require'
//        ];
//        $res = $this->validate($data,$rule);
//        if ($res !== true) {
//            return json(['code' => __LINE__, 'msg' => $res]);
//        }
//        return json( Message::getList($data));
//    }
//    /*
//     * add message from user
//     * */
//   public function save(Request $request){
//       $data = $request->post();
//       $rule=[
//           'username'=>'require','shop_id'=>'require','message'=>'require'
//       ];
//       $res = $this->validate($data,$rule);
//       if ($res !== true) {
//           return json(['code' => __LINE__, 'msg' => $res]);
//       }
//       return json((new Message)->addMessage($data));
//   }
//    /*
//* 用户删除留言
//*
//* zhuangxiu-zyg
//* */
//    public function del_by_user(Request $request) {
//        $data=$request->post();
//        $rule=[
//            'msg_id'=>'require|number',
//        ];
//        $res = $this->validate($data,$rule);
//        if ($res !== true) {
//            return json(['code' => __LINE__, 'msg' => $res]);
//        }
//        return json((new Message)->delMsg($data['msg_id']));
//    }*/

}
