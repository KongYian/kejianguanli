<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2018/6/6
 * Time: 11:09
 */

namespace app\course\controller;


use app\course\model\CourseModel;
use cmf\controller\AdminBaseController;
use helper\ArrayHelper;
use helper\CommonHelper;

class AdminCourseController extends AdminBaseController
{
    /**
     * description:课件列表
     * @return mixed
     */
    public function courseList(){
        $param = $this->request->param();

        $model = new CourseModel();
        $data = $model->courseList($param);

        $typeTree = $model->courseTypeList();
        $this->assign('typeTree',$typeTree);

        $this->assign('courses',$data->items());
        $this->assign('course',$data->render());

        return $this->fetch();
    }

    /**
     * description:添加课件页面
     * @return $this
     */
    public function add(){
        $model = new CourseModel();
        $typeTree = $model->courseTypeList();
        $this->assign('typeTree',$typeTree);
        return $this->fetch();
    }

    /**
     * description:处理课件新增
     */
    public function addPost(){
        $param = $this->request->param();
        if($param){
            $model = new CourseModel();
            if($model->addCourse($param)){
                $this->success('添加成功');
            }else{
                $this->error('添加失败');
            }
        }else{
            $this->error('提交的参数有误');
        }
    }

    /**
     * description:课件编辑页面
     * @return mixed
     */
    public function edit(){
        $param = $this->request->param();
        $id = ArrayHelper::getValue($param,'id');
        if($id){
            $model = new CourseModel();
            $res = $model->getCourseInfo($id);
            $typeTree = $model->courseTypeList($res['course_type_id']);
            $this->assign('typeTree',$typeTree);
            $this->assign('info',$res);
            return $this->fetch();
        }else{
            $this->error('获取课件信息失败');
        }
    }

    /**
     * description:处理课件编辑
     */
    public function editPost(){
        $param = $this->request->param();
        if($param){
            $model = new CourseModel();
            if($model->editCourse($param)){
                $this->success('修改成功');
            }else{
                $this->error('修改失败');
            }
        }else{
            $this->error('提交的参数有误');
        }
    }

    /**
     * description:删除课件
     */
    public function delete(){
        $param = $this->request->param();
        if($param){
            $model = new CourseModel();
            $model->doDelete($param);
            $this->success('删除成功');
        }else{
            $this->error('参数有误');
        }
    }

    public function publish(){
        $param = $this->request->param();
        if($param){
            $model = new CourseModel();
            $model->doPublish($param);
            $this->success('操作成功');
        }else{
            $this->error('参数有误');
        }
    }
}