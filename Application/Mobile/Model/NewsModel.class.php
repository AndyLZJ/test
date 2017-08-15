<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15
 * Time: 11:24
 */

namespace Mobile\Model;
use Think\Model;
/**
 * @首页公开课
 * @author lizhongjian
 *
 */
class NewsModel extends CommonModel
{

    protected $tableName = 'News';//如果模型名称与数据表名称一致这个可以没有，否则该条件必须
    //定义验证规则
    protected $_validate = array(
        array('template', 'require', '资讯所属模板不能为空', Model::EXISTS_VALIDATE),
        array('template', array(1, 2), '资讯所属模板参数有误！', 2, 'in'), // 当值不为空的时候判断是否在一个范围内
        array('type', 'require', '资讯类型不能为空', Model::EXISTS_VALIDATE),
        array('type', array(1, 2, 3, 4), '资讯类型参数有误！', 2, 'in'), // 当值不为空的时候判断是否在一个范围内
        array('title', 'require', '资讯标题不能为空', Model::EXISTS_VALIDATE),
        array('title', '2,20', '标题长度为2-20个字符组成', Model::EXISTS_VALIDATE, 'length'),
        array('content', 'require', '资讯内容不能为空', Model::EXISTS_VALIDATE),
        array('content', '2,200', '内容长度为2-200个字符组成', Model::EXISTS_VALIDATE, 'length'),
    );


    /**
     * @获取资讯列表
     * @$type  资讯所属公司模板  1综合资讯  2公司资讯
     * @$page 分页参数（第几页）
     * @$total  分页参数（每页显示多少条）
     * @$userId   用户id
     */
    public function getNewsList($type, $page, $total, $userId){

        $News = M('News');
        if ($type == 1) {//综合资讯
            $where = array(
                'template' => 2
            );
            $result = $News->where($where)->field('id,title,content,create_time,img')->limit($page, $total)->order('create_time desc')->select();
            foreach($result as $key=>$value){
                $result[$key]['content'] = parent::filterTag($value['content']);
            }
            if(!$result){
                return $this->error(1030, '暂无数据返回');
            }else{
                return $this->success(1000, '获取成功',$result);
            }

        } elseif ($type == 2) {//公司资讯

            //获取登录者当前所在的组织id
            $tissue_id = $this->getTissueId($userId);
            //列表数据取自己公司资讯和全部综合资讯
            $where = array(
                'tissue_id' => $tissue_id,
                'template' => 1,
                "_logic" => "AND"
            );
            $result = $News->where($where)->field('id,title,type,content,create_time,img')->limit($page, $total)->order('create_time desc')->select();
            if (!$result) {
                return $this->error(1030, '暂无数据返回');
            }else{
                foreach ($result as $key => $value) {
                    $result[$key]['content'] = parent::filterTag($value['content']);
                    //资讯类型 1要闻 2培训 3通知 4活动
                    switch ($value['type']) {
                        case 1;
                            $result[$key]['type'] = '【要闻】';
                            break;

                        case 2;
                            $result[$key]['type'] = '【培训】';
                            break;

                        case 3;
                            $result[$key]['type'] = '【通知】';
                            break;

                        case 4;
                            $result[$key]['type'] = '【活动】';
                            break;
                    }
                }
                return $this->success(1000, '获取成功',$result);
            }
        } else {
            return $this->error(1030, '缺少资讯所属模板参数');
        }
    }


    /**
     * @创建资讯
     */
    public function createUpdateNews($data,$method,$userId){
        //获取登录者当前所在的组织id
        $tissueId = $this->getTissueId($userId);
        $data['tissue_id'] = $tissueId;
        $data['uid'] = $userId;
        $data['create_time'] = date('Y-m-d H:i:s', time());
        if ($method == 2) {//编辑
            $id = I('post.id', '', 'trim,htmlspecialchars,int');
            if ($id == '') {
                return $this->error(1030, '编辑id参数不能为空');
            }
            if ($id < 1) {
                return $this->error(1030, '编辑id参数有误');
            }
            $result = M('News')->where(array('id' => $id))->save($data);
            if ($result) {
                return $this->success(1000, '操作成功');
            } else {
                return $this->error(1030, '操作失败');
            }
        }elseif($method == 1){//新建
            $result = M('News')->data($data)->add();
            if ($result) {
                return $this->success(1000, '操作成功');
            } else {
                return $this->error(1030, '操作失败');
            }
        }else{
            return $this->error(1030, '未知操作方式');
        }
    }


    /**
     * @资讯删除
     */
    public function deleteNews($strId){
        if(!empty($strId)){
            //将字符串拼装成数组
            $arrId = explode(',',$strId);
            foreach($arrId as $val){
                $val = intval($val);
                if(!is_int($val)){
                    return $this->error(1030,'删除id参数必须为大于0的非负整数');
                }
                if($val < 0){
                    return $this->error(1030,'删除id参数必须为大于0的非负整数');
                }
                $arr[] = $val;
            }
            $where['id'] = array('in',$arr);
            $info = M('News')->where($where)->delete();
            if($info){
                return $this->success(1000,'操作成功');
            }else{
                return $this->error(1030,'操作失败');
            }
        }else{
            return $this->error(1030,'删除id参数有误');
        }
    }


    /**
     * @获取用户TissueId
     */
    public function getTissueId($userId){
        $tissue_id = M('Tissue_group_access')->where(array('uid'=>$userId))->getField('tissue_id');
        return $tissue_id;
    }
}