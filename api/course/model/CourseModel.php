<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2018/6/7
 * Time: 13:53
 */

namespace api\course\model;


use helper\CommonHelper;
use helper\ExceptionHelper;
use think\Db;
use think\Exception;
use think\Model;

class CourseModel extends Model
{
    const COURSE_PAGESIZE = 10;
    const LIKE_PAGESIZE = 10;
    const COMMENT_PAGESIZE = 10;

    /**
     * description:课件列表分页获取
     * @param int $currentPage
     * @return bool|false|\PDOStatement|string|\think\Collection
     */
    public function courseList($currentPage = 1){
        $res = $this->field(['id','title','teacher','description','course_type_id','thumbnail'])
            ->where(['status'=>1, 'delete'=>0])
            ->order('id desc')
            ->limit(($currentPage-1)*self::COURSE_PAGESIZE,self::COURSE_PAGESIZE)
            ->select();
        if($res){
            return $res;
        }
        return false;
    }

    /**
     * description:获取课件详情页面的信息,课件信息，评论信息,点赞
     * @param int $courseId
     * @param int $userId
     * @return array
     */
    public function courseInfo($courseId = 0,$userId = 0){
        $courseBaseInfo = $this->where(['id'=>$courseId])->find();
        if($courseBaseInfo['delete'] == 1 || $courseBaseInfo['status'] == 0){
            ExceptionHelper::error('当前课件不存在');
        }
        $likeInfo = $this->likeInfo($courseId);
        $commentInfo = $this->commentInfo($courseId);
        $liked = Db::name('course_like')->where(['user_id'=>$userId])->count();
        return [
            'base_info'=>$courseBaseInfo,
            'like_info'=>$likeInfo,
            'comment_info'=>$commentInfo,
            'other_info'=>[
                'has_liked'=>$liked == 0 ? 0 : 1
            ]
        ];
    }

    /**
     * description:赞 分页列表
     * @param int $courseId
     * @param int $currentPage
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function likeInfo($courseId = 0,$currentPage = 1){
        return Db::name('course_like')
            ->field('b.username,b.avatar')
            ->alias('a')
            ->join('course_user b','b.id = a.user_id')
            ->where(['a.course_id'=>$courseId])
            ->order('a.id desc')
            ->limit(($currentPage-1)*self::COMMENT_PAGESIZE,self::COMMENT_PAGESIZE)
            ->select();
    }

    /**
     * description:评论分页列表
     * @param int $courseId
     * @param int $currentPage
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function commentInfo($courseId = 0,$currentPage=1){
        return Db::name('course_comment')
            ->field('b.username,b.avatar,a.content,a.create_time')
            ->alias('a')
            ->join('course_user b','b.id = a.user_id')
            ->where(['a.course_id'=>$courseId,'a.status'=>1])
            ->order('a.id desc')
            ->limit(($currentPage-1)*self::COMMENT_PAGESIZE,self::COMMENT_PAGESIZE)
            ->select();
    }


    /**
     * description:添加评论
     * @param int $userId
     * @param int $courseId
     * @param string $content
     * @return bool
     */
    public function addComment($userId = 0,$courseId = 0,$content = ''){
        $insert = [
            'user_id'=>$userId,
            'course_id'=>$courseId,
            'content'=>$content,
            'create_time'=>time()
        ];
        if(Db::name('course_comment')->insert($insert)){
            $this->where(['id'=>$courseId])->setInc('comments');
            return true;
        }
        return false;
    }

    /**
     * description:点赞接口
     * @param $userId
     * @param $courseId
     * @return bool
     */
    public function addLike($userId,$courseId){
        $insert = [
            'user_id'=>$userId,
            'course_id'=>$courseId,
            'create_time'=>time()
        ];
        if(Db::name('course_like')->insert($insert)){
            $this->where(['id'=>$courseId])->setInc('like');
            return true;
        }
        return false;
    }

    /**
     * description:预览加1
     * @param $userId
     * @param $courseId
     */
    public function addHits($userId,$courseId){
        $insert = [
            'user_id'=>$userId,
            'course_id'=>$courseId,
            'create_time'=>time()
        ];
        Db::name('course_like')->insert($insert);
        $this->where(['id'=>$courseId])->setInc('hits');
    }
}