<?php
/**
 * Created by PhpStorm.
 * User: blue
 * Date: 2018/6/6
 * Time: 17:38
 */

namespace app\course\model;


use helper\ArrayHelper;
use helper\CommonHelper;
use think\Db;
use think\Model;

class CourseModel extends Model
{
    /**
     * description:课程分页列表
     * @param $param
     * @return \think\Paginator
     */
    public function courseList($param){
        $type = ArrayHelper::getValue($param,'type');
        $startTime = ArrayHelper::getValue($param,'start_time');
        $endTime = ArrayHelper::getValue($param,'end_time');
        $keyword = ArrayHelper::getValue($param,'keyword');

        $where= [];

        if($type != 0){
            $where['a.course_type_id'] = $type;
        }
        if($startTime != ''){
            $where['a.create_time']  = ['>',strtotime($startTime)];
        }
        if($endTime != ''){
            $where['a.create_time']  = ['<',strtotime($endTime)];
        }
        if($keyword != ''){
            $where['a.title']  = ['like',"%{$keyword}%"];
        }

        $where['a.delete'] = 0;

        $query = $this->field('a.*,b.name')->alias('a')->join(
            'course_type b','a.course_type_id = b.id'
        );

        $res = $query->where($where)->order('a.id desc')->paginate(15);
        return $res;
    }

    /**
     * description:课程的分类列表
     * @param $selectedCourseId
     * @return string
     */
    public function courseTypeList($selectedCourseId = 0){
        $res = Db::name('course_type')->select();
        $str = '';
        foreach ($res as $item){
            if($item['id'] == $selectedCourseId){
                $str .= "<option value='{$item['id']}' selected>{$item['name']}</option>";
            }else{
                $str .= "<option value='{$item['id']}'>{$item['name']}</option>";
            }
        }
        return $str;
    }

    public function addCourse($param){
        $type = ArrayHelper::getValue($param,'type');
        $description = ArrayHelper::getValue($param,'description');
        $thumbnail = ArrayHelper::getValue($param,'thumbnail');
        $teacher = ArrayHelper::getValue($param,'teacher');
        $title = ArrayHelper::getValue($param,'title');
        $status = ArrayHelper::getValue($param,'status');
        $allowComment = ArrayHelper::getValue($param,'allow_comment');
        $insert = [
            "title"=>$title,
            "description"=>$description,
            "teacher"=>$teacher,
            "status"=>$status,
            "thumbnail"=>$thumbnail,
            "course_type_id"=>$type,
            "allow_comment"=>$allowComment,
            'create_time'=>time(),
            'update_time'=>time()
        ];

        if($type == 1){
            //视频
            $videoUrl = ArrayHelper::getValue($param,'video_url');
            $videoName = ArrayHelper::getValue($param,'video_name');
            $insert['attachment'] = json_encode(['video_url'=>cmf_asset_relative_url($videoUrl),'video_name'=>$videoName],JSON_UNESCAPED_UNICODE);
        }elseif ($type == 2){
            //图片+音频
            $photoUrl = ArrayHelper::getValue($param,'ppt_photo_url');
            $photoName = ArrayHelper::getValue($param,'ppt_photo_name');
            $audioUrl = ArrayHelper::getValue($param,'ppt_audio_url');
            $audioName = ArrayHelper::getValue($param,'ppt_audio_name');
            $insert['attachment'] = json_encode([
                'ppt_photo_url'=>cmf_asset_relative_url($photoUrl),
                'ppt_audio_url'=>cmf_asset_relative_url($audioUrl),
                'ppt_photo_name'=>$photoName,
                'ppt_audio_name'=>$audioName,
            ]);
        }else{
            $insert['attachment'] = '';
        }

        return $this->insert($insert);
    }

    /**
     * description:获取课件信息
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     */
    public function getCourseInfo($id){
        return $this->where(['id'=>$id])->find();
    }

    /**
     * description:课件更新
     * @param $param
     * @return $this
     */
    public function editCourse($param){
        $id = ArrayHelper::getValue($param,'id');
        $type = ArrayHelper::getValue($param,'type');
        $description = ArrayHelper::getValue($param,'description');
        $thumbnail = ArrayHelper::getValue($param,'thumbnail');
        $teacher = ArrayHelper::getValue($param,'teacher');
        $title = ArrayHelper::getValue($param,'title');
        $status = ArrayHelper::getValue($param,'status');
        $allowComment = ArrayHelper::getValue($param,'allow_comment');
        $insert = [
            "title"=>$title,
            "description"=>$description,
            "teacher"=>$teacher,
            "status"=>$status,
            "thumbnail"=>$thumbnail,
            "course_type_id"=>$type,
            "allow_comment"=>$allowComment,
            'update_time'=>time()
        ];

        if($type == 1){
            //视频
            $videoUrl = ArrayHelper::getValue($param,'video_url');
            $videoName = ArrayHelper::getValue($param,'video_name');
            $insert['attachment'] = json_encode(['video_url'=>cmf_asset_relative_url($videoUrl),'video_name'=>$videoName],JSON_UNESCAPED_UNICODE);
        }elseif ($type == 2){
            //图片+音频
            $photoUrl = ArrayHelper::getValue($param,'ppt_photo_url');
            $photoName = ArrayHelper::getValue($param,'ppt_photo_name');
            $audioUrl = ArrayHelper::getValue($param,'ppt_audio_url');
            $audioName = ArrayHelper::getValue($param,'ppt_audio_name');
            $insert['attachment'] = json_encode([
                'ppt_photo_url'=>cmf_asset_relative_url($photoUrl),
                'ppt_audio_url'=>cmf_asset_relative_url($audioUrl),
                'ppt_photo_name'=>$photoName,
                'ppt_audio_name'=>$audioName,
            ]);
        }else{
            $insert['attachment'] = '';
        }

        return $this->where(['id'=>$id])->update($insert);
    }


    /**
     * description:删除课件
     * @param $param
     * @return bool
     */
    public function doDelete($param){
        $id = ArrayHelper::getValue($param,'id');
        $ids = ArrayHelper::getValue($param,'ids');
        if($id){
            $this->where(['id'=>$id])->update(['delete'=>1]);
        }else{
            $this->where(['id' => ['in', $ids]])->update(['delete'=>1]);
        }
        return true;
    }

    /**
     * description:课件发布
     * @param $param
     * @return bool
     */
    public function doPublish($param){
        $id = ArrayHelper::getValue($param,'id');
        $ids = ArrayHelper::getValue($param,'ids');
        $publishType = ArrayHelper::getValue($param,'publish_type');
        if($id){
            $this->where(['id'=>$id])->update(['status'=>$publishType]);
        }else{
            $this->where(['id' => ['in', $ids]])->update(['status'=>$publishType]);
        }
        return true;
    }

}