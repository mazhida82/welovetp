<?php

namespace app\api\model;

use think\Model;

class CartGood extends model {

    public function getStAttr($value) {
        $status = [0 => 'deleted', 1 => '正常'];
        return $status[$value];
    }

    /**
     * 无规格
     * @param $data
     * @return array
     */
    public function addNoSPEC($data){
        $row_ = self::where(['good_id'=>$data['good_id'],'st'=>1])->find();
        if (!$row_) {
            if (!$this->save($data)) {
                return ['code' => __LINE__, 'msg' => '添加失败'];
            }
            return ['code' => 0, 'msg' => '添加成功'];
        }
        $row_->num += $data['num'];
        if (!$row_->save()) {
            return ['code' => __LINE__, 'msg' => '修改失败'];
        }
        return ['code' => 0, 'msg' => '修改成功'];
    }

    /**
     * 有规格
     * @param $data
     */
    public function addHvSPEC($data){
//        dump($data);exit;
        $row = self::where(['good_id'=>$data['good_id'],'st'=>1])->find();
        //商品购物车无商品
        if(!$row){
            if (!$this->save($data)) {
                return ['code' => __LINE__, 'msg' => '添加失败'];
            }
            return ['code' => 0, 'msg' => '添加成功'];
        }
        //有商品的情况
        $res = self::where(['good_id'=>$data['good_id'],'st'=>1,'property_id'=>$data['property_id']])->find();
        //规格id不一致
        if(!$res){
            if (!$this->save($data)) {
                return ['code' => __LINE__, 'msg' => '添加失败'];
            }
            return ['code' => 0, 'msg' => '添加成功'];
        }
        //规格id一致
        $res->num += $data['num'];
        if (!$res->save()) {
            return ['code' => __LINE__, 'msg' => '修改失败'];
        }
        return ['code' => 0, 'msg' => '修改成功'];
    }



    /*
     * useing
     * */
    public static function getGoods($data) {
        $cart_id = $data['id'];
        $list = self::where(['cart_id' => $cart_id , 'wl_cart_good.st' => 1])->select();
        $arr = [];
        foreach($list as $k=>$v){
            $list_ = (new Good())->where(['wl_good.id' => $v['good_id'] , 'wl_good.st' => 1 ])->find();
            $list_['cart_good_id'] = $v['id'];
            $list_['num'] = $v['num'];
            $list_['cart_id'] = $v['cart_id'];
            $list_['property_id'] = $v['property_id'];
            if($v['property_id'] != 0){
                    $property = (new Property())->where(['id' => $v['property_id']])->find();
                    $list_['price'] = $property['price'];
                    $list_['value'] = $property['value'];
            }
            $arr[$k] = $list_;
        }
        return $arr;
    }

    /**
     * 购物车+
     * @param $data
     * @return array
     */
    public  function addGoods($data){
        $list = self::where(['id' => $data['cart_good_id']])->find();
        $data_ = ++$list->num;
        $res = $this->where('id',$data['cart_good_id'])->update(['num' => $data_]);
        if($res){
            return ['code'=>0,'msg'=>'数量+1'];
        }else{
            return ['code'=>__LINE__,'msg'=>'增加数量失败'];
        }
    }

    public function minusGoods($data){
        $list = self::where(['id' => $data['cart_good_id']])->find();
        if($list->num == 1){
            return ['code'=>__LINE__,'msg'=>'受不了了,宝贝不能再减少了哦'];
        }else{
            $data_ = --$list->num;
            $res = $this->where('id',$data['cart_good_id'])->update(['num' => $data_]);
            if($res){
                return ['code'=>0,'msg'=>'数量-1'];
            }else{
                return ['code'=>__LINE__,'msg'=>'减少数量失败'];
            }
        }

    }

}
