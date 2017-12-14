<?php

namespace app\api\model;
use think\model;

class UserCoupon extends Base{
    protected $table = 'wl_users_coupon';

    /**
     * 查询优惠券(限定优惠券ID,用户ID)
     * @param $data
     * @return array|false|\PDOStatement|string|Model
     */
    public static function getList($data){
        $list = self::where(['user_id'=>$data['user_id'],'coupon_id'=>$data['coupon_id'],'st'=>1])->find();
        return $list;
    }

    public static function getListByUser($data){
        $list = self::where(['user_id'=>$data,'st'=>'1'])->join('coupon','wl_users_coupon.coupon_id=coupon.id')->order('end_time asc')->paginate(8);
        return $list;
    }
}