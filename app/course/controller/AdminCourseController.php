<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2018/6/6
 * Time: 11:09
 */

namespace app\course\controller;


use cmf\controller\AdminBaseController;

class AdminCourseController extends AdminBaseController
{
    /**
     * description:课件列表
     * @return mixed
     */
    public function courseList(){
        $this->assign('articles',[]);
        $this->assign('page','');
        return $this->fetch();
    }

    /**
     * description:添加课件
     * @return $this
     */
    public function add(){
        return $this->assign();
    }
}