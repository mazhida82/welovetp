<?php

namespace app\back\controller;

use app\back\model\User;
use think\Request;


class UserController extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        $data = $request->param();
        $rules= ['time_from'=>'date','time_to'=>'date'];
        $msg = ['time_from'=>'日期格式有误','time_to'=>'日期格式有误'];
        $res = $this->validate($data,$rules,$msg);
        if($res!==true){
            $this->error($res);
        }
		$m_users = new User();
		$list = $m_users->getAllUsers($data);

		return $this->fetch('index',['list'=>$list]);
    }


    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request)
    {
		$data = $request->param();
		$res = $this->validate($data,'UsersValidate');
		if($res!==true){
			$this->error($res);
		}
		$user = new User();
		$exists_mobile = $user->where(['mobile'=>$data['mobile'],'id'=>['<>',$data['id']]])->find();
		if($exists_mobile){
			$this->error('mobile is al');
		}
		$user->save($data,['id'=>$data['id']]);
		$this->redirect('index');
    }

    /**
     * soft-delete 指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete(Request $request)
    {
        //
		$id=$request->post('id');
		$row = User::get($id);
		$status = $row->getData('status');
		$status == 1 ? $row->status = 0 : $row->status = 1;
		$row->save();
		$this->redirect('index');
    }

}
