<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台权限管理
 */
class CompetitionController extends AdminBaseController{

//******************权限***********************
    /**
     * 会员竞赛入口
     */
    public function member(){

        //获取用户信息
        $getUserinfo = $this->getUserinfo();

        if($getUserinfo['id'] == 1 || $getUserinfo['id'] == 779 || $getUserinfo['id'] == 778){
            $phone = $this->phone($getUserinfo['id']);
        }else{
            $phone = array($getUserinfo['phone'],$getUserinfo['phone'],$getUserinfo['phone']);
        }


        //银行sing生成
        $bank_sing = $this->Judge($phone[0],"4219",1);

        //保险sing生成
        $insurance_sing = $this->Judge($phone[1],"4220",5);

        //证券sing生成
        $security_sing = $this->Judge($phone[2],"4221",4);

        //银行URL
        $bank_url = "http://dy.cb.occupationedu.com/LoginNews.aspx?urltype=dasai&tel=".$phone[0]."&comid=4219&productid=1&sign=".$bank_sing."&name=".$getUserinfo['username'];

        //保险URL
        $insurance_url = "http://dy.ci.occupationedu.com/LoginNews.aspx?urltype=dasai&tel=".$phone[1]."&comid=4220&productid=5&sign=".$insurance_sing."&name=".$getUserinfo['username'];

        //证券URL
        $security_url = "http://dy.cs.occupationedu.com/LoginNews.aspx?urltype=jiaoxue&tel=".$phone[2]."&comid=4221&productid=4&sign=".$security_sing."&name=".$getUserinfo['username'];

        $data = array(
            'bank_url'=>$bank_url,
            'insurance_url'=>$insurance_url,
            'security_url'=>$security_url
        );

        $this->assign($data);


        $this->display();
    }




    /**
     * 成绩查看
     */
    public function scorecheck(){

        //获取用户信息
        $getUserinfo = $this->getUserinfo();

        //管理员链接不一样
        if($getUserinfo['id'] == 1 || $getUserinfo['id'] == 779 || $getUserinfo['id'] == 778){

            //银行sing生成
            $bank_sing = $this->Judge("","4219",1);

            //保险sing生成
            $insurance_sing = $this->Judge("","4220",5);

            //证券sing生成
            $security_sing = $this->Judge("","4221",4);

            if($getUserinfo['id'] == 1){
                $phone = array(18503036671,13590461172,13632988601);
            }elseif($getUserinfo['id'] == 779){
                $phone = array(13590461172,13590461172,13590461172);
            }elseif($getUserinfo['id'] == 778){
                $phone = array(18503036671,18503036671,18503036671);
            }

            $str = array('bank'=>'&tel2='.$phone[0],'insurance'=>'&tel2='.$phone[1],'security'=>'&tel2='.$phone[2]);

            //银行URL
            $bank_url = "http://www.occupationedu.com/DaSai/BankSecuritiesInsuranceDasai/StudentNewResultquery.aspx?name=".$getUserinfo['username']."&tel=&comid=4219&sign=".$bank_sing."&ProductId=1&urltype=BJ".$str['bank'];

            //保险URL
            $insurance_url = "http://www.occupationedu.com/DaSai/BankSecuritiesInsuranceDasai/StudentNewResultquery.aspx?name=".$getUserinfo['username']."&tel=&comid=4220&sign=".$insurance_sing."&ProductId=5&urltype=BJ".$str['insurance'];

            //证券URL
            $security_url = "http://www.occupationedu.com/DaSai/BankSecuritiesInsuranceDasai/StudentNewResultquery.aspx?name=".$getUserinfo['username']."&tel=&comid=4221&sign=".$security_sing."&ProductId=4&urltype=BJ".$str['security'];

        }else{

               if(in_array($getUserinfo['id'],array(777,776,775,774,773,772,771,770,769))){

                   $getUserinfo = $this->getUserinfo();

                   $phone = array($getUserinfo['phone'],$getUserinfo['phone'],$getUserinfo['phone']);

                   //绑定领队账号
                   $arr = array(18503036671,13590461172);

               }else{

                   $phone = array(13612312312,13590461172,18503036671);

                   $arr = array('','');

               }


            //银行sing生成
            $bank_sing = $this->Judge($phone[0],"4219",1);

            //保险sing生成
            $insurance_sing = $this->Judge($phone[1],"4220",5);

            //证券sing生成
            $security_sing = $this->Judge($phone[2],"4221",4);


            //银行URL
            $bank_url = "http://www.occupationedu.com/DaSai/BankSecuritiesInsuranceDasai/StudentNewResultquery.aspx?name=".$getUserinfo['username']."&tel=".$phone[0]."&comid=4219&sign=".$bank_sing."&ProductId=1&urltype=BJ&tel2=".$arr[0];

            //保险URL
            $insurance_url = "http://www.occupationedu.com/DaSai/BankSecuritiesInsuranceDasai/StudentNewResultquery.aspx?name=".$getUserinfo['username']."&tel=".$phone[1]."&comid=4220&sign=".$insurance_sing."&ProductId=5&urltype=BJ&tel2=".$arr[1];

            //证券URL
            $security_url = "http://www.occupationedu.com/DaSai/BankSecuritiesInsuranceDasai/StudentNewResultquery.aspx?name=".$getUserinfo['username']."&tel=".$phone[2]."&comid=4221&sign=".$security_sing."&ProductId=4&urltype=BJ";

        }

        $data = array(
            'bank_url'=>$bank_url,
            'insurance_url'=>$insurance_url,
            'security_url'=>$security_url
        );

        $this->assign($data);

        $this->display();
    }

    /**
     * 管理员竞赛入口
     */
    public function admin(){

        //获取用户信息
        $getUserinfo = $this->getUserinfo();

        $phone = $this->phone($getUserinfo['id']);

        //银行sing生成
        $bank_sing = $this->Judge($phone[0],"4219",1);

        //保险sing生成
        $insurance_sing = $this->Judge($phone[1],"4220",5);

        //证券sing生成
        $security_sing = $this->Judge($phone[2],"4221",4);


        //银行URL
        $bank_url = "http://dy.cb.occupationedu.com/LoginNews.aspx?urltype=dasaiMana&tel=".$phone[0]."&comid=4219&productid=1&sign=".$bank_sing."&name=".$getUserinfo['username'];

        //保险URL
        $insurance_url = "http://dy.ci.occupationedu.com/LoginNews.aspx?urltype=dasaiMana&tel=".$phone[1]."&comid=4220&productid=5&sign=".$insurance_sing."&name=".$getUserinfo['username'];

        //证券URL
        $security_url = "http://dy.cs.occupationedu.com/LoginNews.aspx?urltype=jiaoxueMana&tel=".$phone[2]."&comid=4221&productid=4&sign=".$security_sing."&name=".$getUserinfo['username'];


        $data = array(
            'bank_url'=>$bank_url,
            'insurance_url'=>$insurance_url,
            'security_url'=>$security_url
        );

        $this->assign($data);

        $this->display();
    }

    /**
     * 成绩管理
     */
    public function scoreadmin(){

        //获取用户信息
        $getUserinfo = $this->getUserinfo();

        $phone = $this->phone($getUserinfo['id']);

        //银行sing生成
        $bank_sing = $this->Judge($phone[0],"4219",1);

        //保险sing生成
        $insurance_sing = $this->Judge($phone[1],"4220",5);

        //证券sing生成
        $security_sing = $this->Judge($phone[2],"4221",4);


        //银行URL
        $bank_url = "http://www.occupationedu.com/DaSai/BankSecuritiesInsuranceDasai/NewResultquery.aspx?name=".$getUserinfo['username']."&tel=".$phone[0]."&comid=4219&sign=".$bank_sing."&ProductId=1&urltype=BJ";

        //保险URL
        $insurance_url = "http://www.occupationedu.com/DaSai/BankSecuritiesInsuranceDasai/NewResultquery.aspx?name=".$getUserinfo['username']."&tel=".$phone[1]."&comid=4220&sign=".$insurance_sing."&ProductId=5&urltype=BJ";

        //证券URL
        $security_url = "http://www.occupationedu.com/DaSai/BankSecuritiesInsuranceDasai/NewResultquery.aspx?name=".$getUserinfo['username']."&tel=".$phone[2]."&comid=4221&sign=".$security_sing."&ProductId=4&urltype=BJ";

        $data = array(
            'bank_url'=>$bank_url,
            'insurance_url'=>$insurance_url,
            'security_url'=>$security_url
        );

        $this->assign($data);


        $this->display();
    }

    /**
     * @param $Telephone
     * @param $Comid
     * @param $ProductId
     * @return mixed
     *
     * 判断sgin值是否与豆芽传递过来的一致
     * 1：豆芽sgin值加密方式：string str = Telephone + Comid + ProductId;（电话+企业ID+类型ID+本月号数+key）进行MD5加密。例：GetMD5（str）转小写
     * 2：然后取得到的MD5加密值 截取前面10位的值加上key的值然后在加上后面所有位的值，然后再加密转小写
     *
     */

    public function Judge($Telephone,$Comid,$ProductId)
    {

        $key = "^dy@2016";

        $xiaoxiekey = strtolower($key);

        $str = $Telephone.$Comid.$ProductId.date("j",time()).$key;

        $keyok = strtolower(md5($str));

        $str1 = substr($keyok,0,10);

        $str2 = substr($keyok,10);

        $str3 = $str1.$xiaoxiekey.$str2;

        $finalCode = strtolower(md5($str3));

        return $finalCode;

    }

    /**
     * 获取当前用户信息
     */
    public function getUserinfo(){

        $user_id = $_SESSION['user']['id'];

        $user_info = M("users a")->field("a.id,a.username,a.phone,b.tissue_id")
            ->join("JOIN __TISSUE_GROUP_ACCESS__ b ON a.id=b.user_id")
            ->where("id=".$user_id)->find();

        return $user_info;

    }

    /**
     * 手机号码
     */
    public function phone($id){

        if($id == 1){
            $phone = array(18503036671,13590461172,13632988601);
        }else if($id == 778){
            $phone = array(18503036671,18503036671,18503036671);
        }else if($id == 779){
            $phone = array(13590461172,13590461172,13590461172);
        }

        return $phone;

    }

}
