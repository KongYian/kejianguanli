<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2018/6/7
 * Time: 10:38
 */

namespace app\course\controller;


use app\course\model\CourseCommentModel;
use cmf\controller\AdminBaseController;
use helper\ArrayHelper;
use helper\CommonHelper;

class AdminCourseCommentController extends AdminBaseController
{
    /**
     * description:评论列表
     * @return mixed
     */
    public function commentList(){
        $param = $this->request->param();
        $id = ArrayHelper::getValue($param,'id');
        if(!$id){
            $this->error('课件参数错误');
        }else{
            $model = new CourseCommentModel();
            $data = $model->getCourseCommentList($param);
            $this->assign('course_id',$id);
            $this->assign('comments',$data->items());
            $this->assign('comment',$data->render());
            return $this->fetch();
        }
    }


    /**
     * description:删除评论
     */
    public function delete(){
        $param = $this->request->param();
        if($param){
            $model = new CourseCommentModel();
            $model->doDelete($param);
            $this->success('删除成功');
        }else{
            $this->error('参数有误');
        }
    }
}