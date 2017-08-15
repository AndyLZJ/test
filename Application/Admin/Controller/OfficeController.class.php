<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * office转化 在线预览
 * author Dujunqiang 20170706
 */
class OfficeController extends AdminBaseController{
    //初始化方法
    public function _initialize(){}
    
	public function index(){
		echo "hello word22222";
	}
	
	/**
	 * 转化office
	 * @param string $inputFile 要转化的office文件  示例：/Upload/xxxxxx/xxxx.doc
	 */
	public function convert($inputFile){
		$inputFile = str_replace("./Upload", "/Upload", $inputFile);
		if(!$inputFile){
			return array("code"=>1001, "message"=>"请掺入文件地址");
		}
		
		$wwwRoot = $_SERVER['DOCUMENT_ROOT'];
		$inputFile = $wwwRoot.$inputFile;
		
		//获取文件后缀
		$fileArr = explode(".", $inputFile);
		$type = end($fileArr);
		$officeType = array("doc", "docx", "ppt", "pptx", "xls", "xlsx", "pdf");
		if(!in_array($type, $officeType)){
			return array("code"=>1003, "message"=>"仅支持doc、docx、ppt、pptx、xls、xlsx、pdf格式转换");
		}
		
		//判断是否有效文件
		if(!is_file($inputFile)){
			return array("code"=>1002, "message"=>"无效的文件");
		}
		
		/* $encode = mb_detect_encoding($inputFile, array("ASCII", "UTF-8", "GB2312", "GBK", "BIG5"));
		if($encode == "ASCII"){
			$inputFile = iconv ( "ASCII", "GBK//IGNORE", $inputFile);
		}else if($encode == "UTF-8"){
			$inputFile = iconv ( "UTF-8", "GBK//IGNORE", $inputFile);
		}else if($encode == "BIG5"){
			$inputFile = iconv ( "BIG5", "GBK//IGNORE", $inputFile);
		} */
		
		if(!function_exists("shell_exec")){
			return array("code"=>1002, "message"=>"shell_exec失效，请设置为生效状态");
		}
		
		//获取文件名称  带 “.”，如test.
		$folderArr = explode("/", $inputFile);
		$inputFileNameAll = end($folderArr);
		$inputFileName = str_replace($type, "", $inputFileNameAll);
		
		$outPath = $wwwRoot."/Upload/docConvert/".date("YmdHis").mt_rand(0, 999);
		mkdir ($outPath, 0777, true);
		
		$env = $wwwRoot."/Upload/docConvert";//soffice环境变量
		if($type == "ppt" || $type == "pptx"){
			$cmd = "soffice -env:UserInstallation=file://$env --invisible --convert-to pdf ".$inputFile." --outdir $outPath";
			$linuxReturn = shell_exec($cmd);
			$outFile = $outPath."/".$inputFileName."pdf";
			$outType = "pdf";
		}elseif($type == "pdf"){
			$linuxReturn = "is pdf";
			$outFile = $inputFile;
			$outType = "pdf";
		}else{
			$cmd = "soffice -env:UserInstallation=file://$env --invisible --convert-to html ".$inputFile." --outdir $outPath";
			$linuxReturn = shell_exec($cmd);
			$outFile = $outPath."/".$inputFileName."html";
			$outType = "html";
		}
		
		if($linuxReturn){
			if($outType == "pdf"){
				//判断pdf大小，超过20M不转换，文件太大，内存资源会耗尽
				if(filesize($outFile) < 20*1024*1024){
					//PDF转图片，失败使用原来的pdf
					$pdf2jpg = shell_exec ( "convert $outFile $outPath/img.jpg" );//成功无信息，失败有错误信息文本输出
					if (!$pdf2jpg) {
						//图片处理为html文件保存
						$html = "<html><head><title>文件预览</title></head><body>";
						for($i = 0; $i < 50; $i ++) {
							$pdfImg = $outPath . "/img-" . $i . ".jpg";
							if (is_file ( $pdfImg )) {
								$pdfImg = str_replace ($wwwRoot."/Upload", "/Upload", $pdfImg );
								$html .= '<img src="' . $pdfImg . '"/>';
							} else {
								$html .= '</body></html>';
								break;
							}
						}
						
						$makeHtml = $outPath . "/" . $inputFileName . "html";
						$newFile = file_put_contents ( $makeHtml, $html );
						if ($newFile) {
							$outType = "html";
							$outFile = $makeHtml;
						}
					}
				}
			}
			$outFile = str_replace($wwwRoot."/Upload", "/Upload", $outFile);
			$return = array("code"=>1000, "message"=>"ok", "new_path"=>$outFile, "type"=>$outType);
		}else{
			rmdir($outPath);
			$return = array("code"=>1004, "message"=>"system error,系统错误", "cmd"=>$cmd);
		}
		return $return;
	}
	
	/**
	 * 预览office
	 * course_id 课程id
	 * chapter_id 章节id
	 */
	public function showDoc(){
		$get = I("get.");
		$course_id = (int)$get["course_id"];
		$chapter_id = (int)$get["chapter_id"];
		if($course_id < 1){
			echo "缺少参数course_id";
			exit;
		}
		if($chapter_id < 0){
			echo "缺少参数chapter_id";
			exit;
		}
		
		$where["course_id"] = $course_id;
		$where["chapter_id"] = $chapter_id;
		$chapFile = M("course_chapter_file")->where($where)->find();
		if($chapFile){
			if($chapFile["file_type"] == "0"){
				$file = ".".$chapFile["view_file_path"];
				$content = file_get_contents($file);
				$content = str_replace("<title></title>", "<title>文件预览</title>", $content);
				echo $content;
			}else{
				$html = "<html><head><title>文件预览</title><style>*{margin:0; padding:0; }</style></head><body>";
				$html .= "<iframe width='100%' height='100%' style='margin:0; padding:0; border:0px;' src='".$chapFile["view_file_path"]."'><iframe>";
				$html .= '</body></html>';
				echo $html;
			}
		}else{
			echo "未找到文件";
			exit;
		}
	}
}