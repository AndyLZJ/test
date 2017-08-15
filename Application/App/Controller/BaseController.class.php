<?php
namespace App\Controller;

use Think\Controller;

/**
 * 基础类
 *
 * 格式化返回数据格式
 * @author  lizhongjian <675283203@qq.com>
 */
class BaseController extends Controller {
    const VERIFY_KEY_PRE = 'MOB';

    var $lat = '';
    var $lng = '';

    /**
     * 格式成功信息
     * @param  string $info 消息
     * @param  array $result 结果
     * @param  array $ext 扩展项
     * @return
     */
    public function success($info, $result = '', $ext = '') {
        $return = array(
            'status' => true,
            'info' => $info
        );
        if ($result) {
            $return['data'] = $result;
        } else {
            $return['data'] = array();
        }
        if ($ext) {
            $return = array_merge($return, $ext);
        }
        $this->ajaxReturn($return);
    }

    /**
     * 格式错误信息
     * @param  string $info 消息
     * @return
     */
    public function error($info, $type = null) {
        $return = array(
            'status' => false,
            'info' => $info
        );
        if (!empty($type)) {
            $this->ajaxReturn($return, $type);
        } else {
            $this->ajaxReturn($return);
        }
    }
}