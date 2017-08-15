<?php
namespace App\Controller;

use Think\Controller;

/**
 * 基础类
 *
 * 格式化返回数据格式
 * @author  lizhongjian <675283203@qq.com>
 */
class DjqTestController extends Controller{
    public function paramShow(){
    	$str = $_GET["data"];
    	if(!$str){
    		echo "请提交data";
    	}
    	$arr = explode(",", $str);
    	foreach ($arr as $value){
    		$arr1 = explode(":", $value);
    		$str2 = (trim($arr1[0]));
    		if($str2 == "") continue;
    		$type = "string";
    		if(strstr($str2, "id") || strstr($str2, "type") || strstr($str2, "status") || strstr($str2, "Type") || strstr($str2, "Status")){
    			$type = "int";
    		}
    		$str2 = str_replace('"', "|", $str2);
    		$str2 .= $type;
    		$str2 .= "||";
    		echo $str2."<br/>";
    	}
    }
    
    public function test(){
    	Vendor("PHPExcel.PHPExcel");
    	$file["tmp_name"] = "Upload/excel/mytest1.xlsx";
    	
		$readType = \PHPExcel_IOFactory::identify($file["tmp_name"]);  //识别文件类型
		$excelReader = \PHPExcel_IOFactory::createReader($readType);
		if($excelReader instanceof \PHPExcel_Reader_CSV){
			$excelReader->setInputEncoding('GBK');
		}
	
		$PHPExcelObj = $excelReader->load($file["tmp_name"]);
		$currentSheet = $PHPExcelObj->getSheet(0);            //选取第一张表单(Sheet1)为当前操作的表单
		$excelRows = $currentSheet->getHighestRow();          //获取最大行
		$excelColumn = $currentSheet->getHighestColumn();     //获取最大列(获取到的是字母 ABCD......)
		$excelColumnIndex = \PHPExcel_Cell::columnIndexFromString($excelColumn);//字母转为数字
	
		if($excelRows > 500){
			$return=array("code" =>15, "message"=>"一次最多导入500行数据");
			return $return;
		}
	
		if($excelColumn > 20){
			$return=array("code" =>16, "message"=>"文件列数不能超过20列");
			return $return;
		}
	
		//没有0行
		$rowTop = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
		$excelContent = array();
		for($i=1; $i<=$excelRows; $i++){
			$rowValue = array();
			$isNullRow = false;
			for($j=0; $j<$excelColumnIndex; $j++){
				$rowValue[$j] = $currentSheet->getCell($rowTop[$j].$i)->getValue();
				if($rowValue[$j]){
					$isNullRow = true;
				}
			}
			if(!$isNullRow){
				continue;
			}
			array_push($excelContent, $rowValue);
		}
    	
    	print_r($excelContent);
    }
}