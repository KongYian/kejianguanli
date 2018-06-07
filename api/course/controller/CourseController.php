<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2018/6/7
 * Time: 13:52
 */

namespace api\course\controller;


use api\course\model\CourseModel;
use cmf\controller\RestBaseController;
use helper\ArrayHelper;
use think\Request;

class CourseController extends RestBaseController
{
    protected $courseModel;

    public function __construct(CourseModel $courseModel)
    {
        parent::__construct();
        $this->courseModel = $courseModel;
    }

    /**
     * description:获取课件列表
     */
    public function courseList(){
        $param = $this->request->param();
        $currentPage = ArrayHelper::getValue($param,'currentPage',1);
        $res = $this->courseModel->courseList($currentPage);
        if($res != false){
            $this->success('获取课件列表成功',$res);
        }else{
            $this->error('获取课件列表失败');
        }
    }

    /**
     * description:可将详情
     */
    public function courseInfo(){
        $param = $this->request->param();
        $courseId = ArrayHelper::getValue($param,'course_id');
        $userId = ArrayHelper::getValue($param,'user_id');
        if($courseId == '' || $userId == ''){
            $this->error('获取课件详情失败');
        }
        $res = $this->courseModel->courseInfo($courseId,$userId);
        $this->addHits($userId,$courseId);
        $this->success('获取课件详情成功',$res);
    }

    /**
     * description:评论分页列表
     */
    public function commentList(){
        $param = $this->request->param();
        $currentPage = ArrayHelper::getValue($param,'currentPage',1);
        $courseId = ArrayHelper::getValue($param,'course_id',0);
        if(!$courseId){
            $this->error('获取评论失败');
        }
        $res = $this->courseModel->commentInfo($courseId,$currentPage);
        if($res != false){
            $this->success('获取课件列表成功',$res);
        }else{
            $this->error('获取课件列表失败');
        }
    }

    /**
     * description:添加评论
     */
    public function addCommmetPost(){
        $param = $this->request->param();
        $userId = ArrayHelper::getValue($param,'user_id');
        $courseId = ArrayHelper::getValue($param,'course_id');
        $content = ArrayHelper::getValue($param,'content');
        if(!$userId || !$courseId || !$content){
            $this->error('提交的评论信息有误');
        }
        if($this->courseModel->addComment($userId,$courseId,$content)){
            $this->success('评论成功');
        }
        $this->error('评论失败');
    }

    /**
     * description:点赞
     */
    public function addLikePost(){
        $param = $this->request->param();
        $userId = ArrayHelper::getValue($param,'user_id');
        $courseId = ArrayHelper::getValue($param,'course_id');
        if(!$userId || !$courseId){
            $this->error('提交的评论信息有误');
        }
        if($this->courseModel->addLike($userId,$courseId)){
            $this->success('点赞成功');
        }
        $this->error('点赞失败');
    }

    /**
     * description:增加点击
     * @param $userId
     * @param $courseId
     */
    public function addHits($userId,$courseId){
        $this->courseModel->addHits($userId,$courseId);
    }
}