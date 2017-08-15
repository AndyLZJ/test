<?php
namespace Admin\Controller;

use Common\Controller\AdminBaseController;

/**
 * 培训管理控制器
 */
class TestDjqController extends AdminBaseController{
    
	public function test(){
		$inputFile = "/Upload/excelTmp/survey_tmp.xlsx";
		$inputFile = "/Upload/docConvert/survey888.csv";
		
		$inputFile = "/Upload/docConvert/test.pptx";
		$inputFile = "/Upload/docConvert/survey999.xls";
		$inputFile = "/Upload/docConvert/太平需求开发安排20170712.xlsx";
		$aa = A("Office")->convert($inputFile);
		print_r($aa);
		exit;
	}
	
	public function testCheck(){
		$this->display("/Test/testCheck");
	}
	
	
	public function test2(){
		
		$file = "/test/PHP经典实例.pdf";
		echo filesize($file);
		echo "<hr>";
		echo 20*1024*1024;
		exit;
		$httppath = "http://v1.occupationedu.com/word%E7%AB%A0%E8%8A%82.docx";
		$fileArr = explode("/", $httppath);
		$inputFileName = end($fileArr);

        $inputPath = "./Upload/docConvert/fileGet";
		mkdir ($inputPath, 0777, true);
		$inputFile = $inputPath."/".$inputFileName;  

		$cnt = file_get_contents($httppath);           
        file_put_contents($inputFile, $cnt);
        
        echo $inputFile."<hr>";
        
        $message = A("Office")->convert($inputFile);
        print_r($message);
	}
}
