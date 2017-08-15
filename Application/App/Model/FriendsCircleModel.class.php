<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/4
 * Time: 13:39
 */

namespace App\Model;

use Think\Model;
     /*
      * 朋友圈模型
      */
class FriendsCircleModel extends CommonModel
{
    //protected $tablePrefix = 'think_';
    //protected $tableName = 'designated_personnel';


    /*
     * 获取工作圈列表信息
     */
    public function friendsCircleList($get,$userId){
        //$uid 对应如果有用户id为$uid的就显示删除功能

        $get['pageNum'] =  15;
        $start = ($get['page'] - 1)*$get['pageNum'];
        $end = $get['pageNum'];
        $condition = array();
        $condition['status'] = 1;
        $condition['pid'] = 0;
        $condition['content'] = array('NEQ','');
        $comList = M("FriendsCircle")->where($condition)->order("publish_time DESC")->limit($start, $end)->select();

        //获取子评论/回复
        foreach ($comList as $key=>$value){

            $user = M("Users")->field("username,avatar")->where(array('id' => $value["uid"]))->limit(1)->select();
            $comList[$key]["username"] = $user[0]["username"];
            $comList[$key]["avatar"] = $user[0]["avatar"];
            $comList[$key]['content'] = str_replace('&nbsp;','',strip_tags($value['content']));;
            $zan = M("Friends_praise")->where("praise=1 AND cid=".$value["id"])->count();
            $comList[$key]["praise_total"] = $zan;//总赞

            $zanStatus = M("Friends_praise")->where("cid=".$value["id"]." AND uid=".$userId)->limit(1)->find();
            if($zanStatus['praise'] == 0){
                ////我是否赞过  1已赞 0未赞
                $comList[$key]["is_praise"] = 0;
            }else{
                $comList[$key]["is_praise"] = 1;
            }

            //是否可删除
            $comList[$key]["is_myFriends"] = 0;
            if($userId == $value["uid"]){
                $comList[$key]["is_myFriends"] = 1;
            }

            $subList = array();
            $pids = self::getFriendCommentChild($value["id"], $value["id"].",");
            $pids = substr($pids, 0, -1);

            if($pids){
                $sonCon = M("FriendsCircle")->where("pid in (".$pids.")")->select();

                if($sonCon){
                    $userCache = array();
                    foreach ($sonCon as $sk=>$sv){

                        $subUser = M("users")->field("id,username,avatar")->where("id=".$sv["uid"])->limit(1)->select();
                        $sonCon[$sk]["username"] = $subUser[0]["username"];
                        $sonCon[$sk]["avatar"] = $subUser[0]["avatar"];
                        $userCache[$sv["id"]] = $subUser[0]["username"];;
                        $sonCon[$key]['content'] = str_replace('&nbsp;','',strip_tags($sv['content']));
                        //是否可是我的工作圈显示删除按钮
                        $sonCon[$sk]["is_myFriends"] = 0;
                        if($userId == $sv["uid"]){
                            $sonCon[$sk]["is_myFriends"] = 1;
                        }


                        if($sv["pid"] != $value["id"]){
                            $sonCon[$sk]["reply_user"] = $userCache[$sv["pid"]];
                        }
                    }

                    $comList[$key]["child"] = $sonCon;

                }
                //统计所有子评论条数
                $comList[$key]["sum"] = count($sonCon);
            }
        }

        if($comList){
            return $this->success(1000,'获取数据成功',$comList);
        }else{
            return $this->error(1030,'赞无数据返回');
        }
    }





    //获取子评论pid
    public function getFriendCommentChild($cid, $cidStr){
        $cid += 0;
        if(!is_int($cid) || $cid < 0){
            return false;
        }
        $cat = M("FriendsCircle")->where("pid=".$cid)->select();
        if($cat){
            foreach ($cat as $key=>$v){
                $cidStr .= $v["id"] . ",";
                $cidStr = self::getFriendCommentChild($v["id"], $cidStr);
            }
        }
        return $cidStr;
    }

  /*
   * 获取回复评论（页面用于数据实时刷新）
   *
   */
    public function getChildComment($id,$type,$userId){

         if($id === ''){
             return $this->error(1023,'缺少回复评论id');
         }

        if($type == 1){
            //主评论
            //点击工作圈列表评论，传主评论id
            $data = M("FriendsCircle")->where(array("id" => $id))->limit(1)->find();
            //获取发布评论人的头像和用户名
            $user = M("Users")->field("username,avatar")->where(array('id' => $data["uid"]))->limit(1)->find();
            $comList["id"] = $data["id"];
            $comList["uid"] = $data["uid"];
            $comList["content"] = str_replace('&nbsp;','',strip_tags($data["content"]));
            $comList["images"] = $data["images"];
            $comList["publish_time"] = $data["publish_time"];
            $comList["status"] = $data["status"];
            $comList["pid"] = $data["pid"];
            $comList["username"] = $user["username"];
            $comList["avatar"] = $user["avatar"];

            $zan = M("Friends_praise")->where("praise=1 AND cid=".$data["id"])->count();
            $comList["praise_total"] = $zan;//总赞

            $zanStatus = M("Friends_praise")->where("cid=".$data["id"]." AND uid=".$data["uid"])->limit(1)->select();
            if(!$zanStatus){
                $zanStatus = "0";
            }else{
                $zanStatus = $zanStatus["praise"] + 0;
            }
            $comList["is_praise"] = $zanStatus;//我是否赞过  1已赞 0未赞

            //是否可删除
            $comList["is_myFriends"] = 0;
            if($userId == $data["uid"]){
                $comList["is_myFriends"] = 1;
            }

            $subList = array();
            $pids = self::getFriendCommentChild($data["id"], $data["id"].",");
            $pids = substr($pids, 0, -1);

        }else{
            //接收子评论id，递归查找父级id
            $data = M("FriendsCircle")->where(array("id" => $id))->limit(1)->find();
            //根据子类id查询第一级id
            $infoId = $this->findParentId($data['pid']);
            $info = M("FriendsCircle")->where(array("id" => $infoId))->limit(1)->find();
            if($info == null){
                return $this->error(1023,'评论id参数有误');
            }
            //获取子评论/回复
            //获取该子评论相对应的主评论人的头像和用户名
            $user = M("Users")->field("username,avatar")->where(array('id' => $info["uid"]))->limit(1)->find();
            $comList["id"] = $info["id"];
            $comList["uid"] = $info["uid"];
            $comList["content"] = str_replace('&nbsp;','',strip_tags($info["content"]));;
            $comList["images"] = $info["images"];
            $comList["publish_time"] = $info["publish_time"];
            $comList["status"] = $info["status"];
            $comList["pid"] = $info["pid"];
            $comList["username"] = $user["username"];
            $comList["avatar"] = $user["avatar"];

            $zan = M("Friends_praise")->where("praise=1 AND cid=".$info["id"])->count();
            $comList["praise_total"] = $zan;//总赞

            $zanStatus = M("Friends_praise")->where("cid=".$info["id"]." AND uid=".$info["uid"])->limit(1)->select();
            if(!$zanStatus){
                $zanStatus = "0";
            }else{
                $zanStatus = $zanStatus["praise"] + 0;
            }
            $comList["is_praise"] = $zanStatus;//我是否赞过  1已赞 0未赞

            //是否可删除
            $comList["is_myFriends"] = 0;
            if($userId == $info["uid"]){
                $comList["is_myFriends"] = 1;
            }

            $subList = array();
            $pids = self::getFriendCommentChild($info["id"], $info["id"].",");
            $pids = substr($pids, 0, -1);
        }
            if($pids){
                $sonCon = M("FriendsCircle")->where("pid in (".$pids.")")->select();
                if($sonCon){
                    $userCache = array();
                    foreach ($sonCon as $sk=>$sv){
                        //上一条评论的用户的头像和用户名
                        $subUser = M("users")->field("username,avatar")->where("id=".$sv["uid"])->limit(1)->select();
                        //获取子评论上一条评论的用户名
                        $sonCon[$sk]["content"] = str_replace('&nbsp;','',strip_tags($sv["content"]));
                        $sonCon[$sk]["username"] = $subUser[0]["username"];
                        $sonCon[$sk]["avatar"] = $subUser[0]["avatar"];
                        $userCache[$sv["id"]] = $subUser[0]["username"];
                        $sonCon[$sk]["touid"] =  M("FriendsCircle")->where(array("id" => $sv["pid"]))->getField('uid');
                        //是否可是我的工作圈显示删除按钮
                        $sonCon[$sk]["is_myFriends"] = 0;
                        if($userId == $sv["uid"]){
                            $sonCon[$sk]["is_myFriends"] = 1;
                        }
                        if($sv["pid"] != $info["id"]){
                            $sonCon[$sk]["reply_user"] = $userCache[$sv["pid"]];;
                        }
                    }

                    $comList["child"] = $sonCon;

                }
                //统计所有子评论条数
                $comList["sum"] = count($sonCon);
            }
        if($comList){
            return $this->success(1000,'获取数据成功',$comList);
        }else{
            return $this->error(1030,'赞无数据返回');
        }
    }


    /**
     * 删除评价
     * comment_id 评价id
     */
    public function delComment($get,$userId){

        if($get['id'] == ''){
            return $this->error(1023,'评论id不能为空');
        }
        $resp = M("FriendsCircle")->where(array("id" => $get['id']))->limit(1)->delete();
        $result = M("Friends_praise")->where(array("cid" => $get['id'],'uid'=>$userId))->limit(1)->delete();
        if($resp) {
            //删除关联的回复评论
            $pids = self::getFriendCommentChild($get['id'], $get['id'] . ",");
            $pidp = substr($pids, 0, -1);
            if ($pidp) {
                $where['pid'] = array('in',$pidp);
                $res = M("FriendsCircle")->where($where)->delete();
                return $res;
            }
        }
    }


    /*
     * 发布工作圈输入数据合法性校验,并作插入操作
     * @param $post 输入数据集
     * @param $uid 用户id
     */
    public function checkData($content,$image,$userId){
        if($content == ''){
            return $this->error(1030,'不能发布空内容');
        }else{
            $data['content'] = $content;
            $data['images'] = $image ? $image : '';
            $data['uid'] = $userId;
            $data['publish_time'] = date('Y-m-d H:i:s',time());
            $data['pid'] = 0;//发布评论pid为0
            $data['status'] = 0;//发布评论为待审核状态
            $res['id'] = $this->data($data)->add();
            $res['uid'] = $userId;
            if($res['id']){
                return $this->success(1000,'操作成功',$res);
            }else{
                return $this->error(1030,'操作失败');
            }
        }
    }

    /*
     * 评论和回复评论
     */
    public function addComment($post,$userId){
        //回复一级评论，需要参数：一级评论id
        if(isset($post['id']) && !empty($post['id'])){
            $data['pid'] = $post['id'];
            $data['uid'] = $userId;
            $data['content'] = $post['content'];
            if(empty($post['content'])){
                return $this->error(1030,'请输入内容');
            }
            $data['status'] = 1;//子评论状态为1，即已通过审核的
            $data['publish_time'] = date('Y-m-d H:i:s',time());
            $id = $this->data($data)->add();
            if($id){
                $info = $this->field('id,uid,content,publish_time,pid')->where(array('id'=>$id))->find();
                return $this->success(1000,'操作成功',$info);
            }else{
                return $this->error(1030,'操作失败');
            }
        }else{

            return $this->error(1023,'缺少必要参数');die();
        }
    }

    /*
     * 回复评论
     */
    public function replyComment($post){
        $data['pid'] = $post['id'];
        $data['author_id'] = $post['uid'];
        $data['reply_content'] = $post['reply_content'];
        $data['reply_time'] = date('Y-m-d H:i:s',time());
        if(empty($data['pid'])){
            $this->error(1023,'缺少必要参数');die();
        }
        if(empty($data['author_id'])){
            $this->error(1023,'缺少必要参数');die();
        }
        if(empty($data['reply_content'])){
            return $this->error(1030,'请输入内容');
        }
        $id = M('FriendsReplycomment')->data($data)->add();
        if($id){
            $info = M('FriendsReplycomment')->where(array('id'=>$id))->find();
            return $this->success(1000,'操作成功',$info);
        }else{
            return $this->error(1030,'操作失败');
        }
    }

    /**
     *工作圈-点赞
     * $uid 点赞者id
     * $content_id 课程content_id
     *
     */
    public function doPraise($get,$uid){
        $friendsPraise = M('FriendsPraise');
        $data['uid'] = $uid;
        $data['cid'] = $get['content_id'];
        if(empty($data['cid'])){
            return $this->error(1023,'参数有误');die();
        }
        settype($get['type'], "string");
        if($get['type'] == '0' || $get['type'] == '1'){
            if($get['type'] == 0){//取消点赞
                $praise = $friendsPraise->where($data)->getField('praise');
                if($praise == 0){
                    return $this->error(1031,'不能重复操作');
                }else{
                    $data['praise'] = 0;
                    $data['praise_time'] = date('Y-m-d H:i:s',time());
                    $friendsPraise->where(array('uid'=>$uid,'cid'=>$data['cid']))->save($data);
                    $praise_total = $friendsPraise->where(array('cid'=>$data['cid']))->sum('praise');
                    $data['praise_total'] = $praise_total;
                    return $this->success(1000,'操作成功',$data);
                }
            }else{//点赞
                $res = $friendsPraise->field('praise')->where($data)->find();
                if($res['praise'] == null){
                    $data['praise'] = 1;
                    $data['praise_time'] = date('Y-m-d H:i:s',time());
                    $friendsPraise->data($data)->add();
                    //统计点赞总数
                    $praise_total = $friendsPraise->where(array('cid'=>$data['cid']))->sum('praise');
                    $data['praise_total'] = $praise_total;
                    return $this->success(1000,'操作成功',$data);
                }elseif($res['praise'] == 0){
                    $data['praise'] = 1;
                    $data['praise_time'] = date('Y-m-d H:i:s',time());
                    $friendsPraise->where(array('uid'=>$uid,'cid'=>$data['cid']))->save($data);
                    //统计点赞总数
                    $praise_total = $friendsPraise->where(array('cid'=>$data['cid']))->sum('praise');
                    $data['praise_total'] = $praise_total;
                    return $this->success(1000,'操作成功',$data);
                }else{
                    return $this->error(1031,'不能重复操作');
                }
            }
        }else{
            return $this->error(1023,'参数有误');die();
        }
    }

}