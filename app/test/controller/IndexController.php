<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2018/6/7
 * Time: 17:29
 */

namespace app\test\controller;


use cmf\controller\HomeBaseController;
use helper\ArrayHelper;

class IndexController extends HomeBaseController
{
    public function index(){
        return $this->fetch(':index');
    }

    public function detailVideo(){
        $param = $this->request->param();
        $userId = ArrayHelper::getValue($param,'user_id');
        $courseId = ArrayHelper::getValue($param,'course_id');
        $this->assign('user_id',$userId);
        $this->assign('course_id',$courseId);
        return $this->fetch(':detail_video');
    }
    public function detailPpt(){
        $param = $this->request->param();
        $userId = ArrayHelper::getValue($param,'user_id');
        $courseId = ArrayHelper::getValue($param,'course_id');
        $this->assign('user_id',$userId);
        $this->assign('course_id',$courseId);
        return $this->fetch(':detail_ppt');
    }
}