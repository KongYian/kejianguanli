<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2018/6/7
 * Time: 10:44
 */

namespace app\course\model;


use helper\ArrayHelper;
use think\Model;

class CourseCommentModel extends Model
{
    /**
     * description:评论列表
     * @param $param
     * @return \think\Paginator
     */
    public function getCourseCommentList($param){
        $id = ArrayHelper::getValue($param,'id');
        $startTime = ArrayHelper::getValue($param,'start_time');
        $endTime = ArrayHelper::getValue($param,'end_time');
        $keyword = ArrayHelper::getValue($param,'keyword');

        $where= [];

        if($startTime != ''){
            $where['a.create_time']  = ['>',strtotime($startTime)];
        }
        if($endTime != ''){
            $where['a.create_time']  = ['<',strtotime($endTime)];
        }
        if($keyword != ''){
            $where['a.content']  = ['like',"%{$keyword}%"];
        }

        $query = $this->field('a.*,b.username,b.avatar,c.title')->alias('a')
            ->join('course_user b','a.user_id = b.id')
            ->join('course c','c.id = a.course_id')
        ;
        $where['a.course_id'] = $id;
        $where['a.status'] = 1;

        $res = $query->where($where)->order('a.id desc')->paginate(15);
        return $res;
    }

    /**
     * description:删除评论
     * @param $param
     * @return bool
     */
    public function doDelete($param){
        $id = ArrayHelper::getValue($param,'id');
        $ids = ArrayHelper::getValue($param,'ids');
        if($id){
            $this->where(['id'=>$id])->update(['status'=>0]);
        }else{
            $this->where(['id' => ['in', $ids]])->update(['status'=>0]);
        }
        return true;
    }
}