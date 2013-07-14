<?php
/**  
* 加水印类，支持文字、图片水印以及对透明度的设置、水印图片背景透明。  
* 代码来源自网络。。作者简单修改，让其适应该CMS的环境
*/
class WaterMark
{  
    /**   
    * 水印类型
    * @var int $waterType 0为文字水印 ；1为图片水印  
    */
    private $waterType;
    /**
    * 水印位置 类型
    * @var int $pos  默认为9(右下角)
    */
    private $pos;
    /**
    * 水印透明度 
    * @var int  $transparent  水印透明度(值越小越透明)
    */
    private $transparent;
    /**
    * 如果是文字水印，则需要加的水印文字
    * @var string $waterStr  默认值 
    */
    private $waterStr;
    /**
    * 文字字体大小  
    * @var int $fontSize  字体大小
    */
    private $fontSize;
    
    /**
    * 水印文字颜色（RGB）  
    * @var array $fontColor  水印文字颜色（RGB）  
    */
    private $fontColor;
    
    /**
    * 字体文件  
    * @var unknown_type
    */
    private $fontFile;
    
    /**
    * 水印图片  
    * @var string $waterImg
    */
    private $waterImg = '';
    
    /**
    * 需要添加水印的图片  
    * @var string $srcImg
    */
    private $srcImg = '';
    
    /**
    * 图片句柄  
    * @var string $im
    */
    private $im = '';
    
    /**
    * 水印图片句柄  
    * @var string $water_im  
    */
    private $water_im = '';
    
    /**
    * 图片信息  
    * @var array  $srcImg_info
    */
    private $srcImg_info = '';
    
    /**
    * 水印图片信息  
    * @var array $waterImg_info  
    */
    private $waterImg_info = '';
    
    /**
    * 水印文字宽度  
    * @var int $str_w 
    */
    private $str_w = '';
    
    /**
    * 水印文字高度  
    * @var int $str_h  
    */
    private $str_h = '';
    
    /**
    * 水印X坐标  
    * @var int $x
    */
    private $x = '';
    
    /**
    * 水印y坐标  
    * @var int $y
    */
    private $y = '';
    
    /**
     * 判断是否在附件上传时发生的错误
     */
    var $isInUpload;
    
    /**
     * 附件上传时的CKEDITOR的js脚本函数号
     */
    var $uploadCK_funcNum;
    
    var $config;
    
    /**
    * 构造函数，通过传入需要加水印的源图片初始化源图片
    * @param string $img  需要加水印的源图片
    */
    public function __construct($img,$isuser_need = 'yes',$isinUpload = false,$uploadCK_funcNum = 0)
    {
    	global $zengl_cms_full_domain;
    	global $ZLCfg_FileFontDir;
    	$this->isInUpload = $isinUpload;
    	$this->uploadCK_funcNum = $uploadCK_funcNum;
    	$this->config = config_get_db_setting('watermark');
        if (file_exists($img)) { //源文件存在 
            $this->srcImg = $img;
            if($this->config['watermark_text'] == '')
          	  	$this->waterStr = $zengl_cms_full_domain;
            else
            	$this->waterStr = $this->config['watermark_text'];
            $this->waterImg = 'images/'.$this->config['watermark_imgfilename'];
            $this->waterType = $this->config['watermark_type'];
            $this->transparent = $this->config['watermark_transparent'];
            $this->pos = $this->config['watermark_pos'];
            $this->fontSize = $this->config['watermark_fontsize'];
            $this->fontFile = $ZLCfg_FileFontDir . $this->config['watermark_fontfilename'];
            if($this->waterType == 0 && !file_exists( $this->fontFile))
            	$this->errorExit('字体文件' . $this->fontFile . '不存在，请检查看文件路径是否正确');
            if($isuser_need == 'yes')
            	$this->config['watermark_switch'] = 'on';
            else
            	$this->config['watermark_switch'] = 'off';
        } else { //源文件不存在 
            $this->errorExit('源文件' . $img . '不存在，请检查看文件路径是否正确');
        }
    }
    
    public function errorExit($message)
    {
    	if($this->isInUpload)
    	{
    		exit("<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction({$this->uploadCK_funcNum}, '', '{$message}');</script>");
    	}
    	else
    		new error('ZENGLCMS附件上传情况：',$message,true,true);
    }
    
    /**
    * 获取需要添加水印的图片的信息，并载入图片
    */
    public function imginfo()
    {
        $this->srcImg_info = getimagesize($this->srcImg);
        switch ($this->srcImg_info[2]) {
            case 3: //png 
                $this->im = imagecreatefrompng($this->srcImg);
                break ;
            case 2: //  jpeg/jpg
                $this->im = imagecreatefromjpeg($this->srcImg);
                break ;
            case 1: //gif 
                $this->im = imagecreatefromgif($this->srcImg);
                break ;
            default:
                $this->errorExit('源图片文件' . $this->srcImg . '格式不正确，目前CMS只支持PNG、JPEG、GIF图片水印功能');
        }
    }
    
    /**
    * 获取水印图片的信息，并载入图片
    */
    private function waterimginfo()
    {
        $this->waterImg_info = getimagesize($this->waterImg);
        switch ($this->waterImg_info[2]) {
            case 3:
                $this->water_im = imagecreatefrompng($this->waterImg);
                break ;
            case 2:
                $this->water_im = imagecreatefromjpeg($this->waterImg);
                break ;
            case 1:
                $this->water_im = imagecreatefromgif($this->waterImg);
                break ;
            default:
                $this->errorExit('水印图片文件' . $this->srcImg . '格式不正确，目前本CMS只支持PNG、JPEG、GIF图片水印功能');
        }
    }
    
    /**
    * 水印位置算法  
    */
    private function waterpos()
    {
        switch ($this->pos) {
            case 0: //随机位置    
                $this->x = rand(0, $this->srcImg_info[0] - $this->waterImg_info[0]);
                $this->y = rand(0, $this->srcImg_info[1] - $this->waterImg_info[1]);
                break ;
            case 1: //上左    
                $this->x = 20;
                $this->y = 20;
                break ;
            case 2: //上中   
                $this->x = ($this->srcImg_info[0] - $this->waterImg_info[0])/2;
                $this->y = 20;
                break ;
            case 3: //上右   
                $this->x = $this->srcImg_info[0] - $this->waterImg_info[0];
                $this->y = 20;
                break ;
            case 4: //中左   
                $this->x = 20;
                $this->y = ($this->srcImg_info[1] - $this->waterImg_info[1]) / 2;
                break ;
            case 5: //中中 
                $this->x = ($this->srcImg_info[0] - $this->waterImg_info[0]) / 2;
                $this->y = ($this->srcImg_info[1] - $this->waterImg_info[1]) / 2;
                break ;
            case 6: //中右   
                $this->x = $this->srcImg_info[0] - $this->waterImg_info[0] - 20;
                $this->y = ($this->srcImg_info[1] - $this->waterImg_info[1]) / 2;
                break ;
            case 7: //下左   
                $this->x = 20;
                $this->y = $this->srcImg_info[1] - $this->waterImg_info[1] - 20;
                break ;
            case 8: //下中
                $this->x = ($this->srcImg_info[0] - $this->waterImg_info[0]) / 2;
                $this->y = $this->srcImg_info[1] - $this->waterImg_info[1] - 20;
                break ;
            case 9: //下右  
                $this->x = $this->srcImg_info[0] - $this->waterImg_info[0] - 20;
                $this->y = $this->srcImg_info[1] - $this->waterImg_info[1] - 20;
                break ;
            default: //下右 
                $this->x = $this->srcImg_info[0] - $this->waterImg_info[0] - 20;
                $this->y = $this->srcImg_info[1] - $this->waterImg_info[1] - 20;
                break ;
        }
    }
    
    /**
    * 加图片水印
    */
    private function waterimg()
    {
        if ($this->srcImg_info[0] <= $this->waterImg_info[0] || $this->srcImg_info[1] <= $this->waterImg_info[1]) {
            //$this->errorExit('图片尺寸太小，无法加水印，请上传一张大图片');
            return 'watermark_bigthen_srcimg';
        }
        //计算水印位置 
        $this->waterpos();
        $cut = imagecreatetruecolor($this->waterImg_info[0], $this->waterImg_info[1]);
        imagecopy($cut, $this->im, 0, 0, $this->x, $this->y, $this->waterImg_info[0], $this->waterImg_info[1]);
        $pct = $this->transparent;
        imagecopy($cut, $this->water_im, 0, 0, 0, 0, $this->waterImg_info[0], $this->waterImg_info[1]);
        //将图片与水印图片合成 
        imagecopymerge($this->im, $cut, $this->x, $this->y, 0, 0, $this->waterImg_info[0], $this->waterImg_info[1], $pct);
    }
    
    /**
    * 加文字水印
    */
    private function waterstr()
    {
        $rect = imagettfbbox($this->fontSize, 0, $this->fontFile, $this->waterStr);
        $w = abs($rect[2] - $rect[6]);
        $h = abs($rect[3] - $rect[7]);
        $fontHeight = $this->fontSize;
        $this->water_im = imagecreatetruecolor($w, $h);
        imagealphablending($this->water_im, false);
        imagesavealpha($this->water_im, true);
        $white_alpha = imagecolorallocatealpha($this->water_im, 255, 255, 255, 127);
        imagefill($this->water_im, 0, 0, $white_alpha);
        if(preg_match("/([a-f0-9][a-f0-9])([a-f0-9][a-f0-9])([a-f0-9][a-f0-9])/i", 
           $this->config['watermark_fontcolor'], $color)) {
	        	$this->fontColor[0] = hexdec($color[1]); //red
	        	$this->fontColor[1] = hexdec($color[2]); //green
	        	$this->fontColor[2] = hexdec($color[3]); //blue
        } else {
        		$this->fontColor = array(255,255,255);
        }
        $color = imagecolorallocate($this->water_im, $this->fontColor[0], $this->fontColor[1], $this->fontColor[2]);
        imagettftext($this->water_im, $this->fontSize, 0, 0, $this->fontSize, $color, $this->fontFile, $this->waterStr);
        $this->waterImg_info = array(
            0 => $w,
            1 => $h
        );
        return $this->waterimg();
    }
    
    /**
    * 水印图片输出
    */
    public function output()
    {
    	if($this->config['watermark_switch'] == 'off')
    		return ;
        $this->imginfo();
        if ($this->waterType == 0) {
            if($this->waterstr()=='watermark_bigthen_srcimg')
            {
            	return;
            }
        } else {
            $this->waterimginfo();
            if($this->waterimg()=='watermark_bigthen_srcimg')
            {
            	return;
            }
        }
        
        switch ($this->srcImg_info[2]) {
            case 3:
                imagepng($this->im, $this->srcImg);
                break ;
            case 2:
                imagejpeg($this->im, $this->srcImg);
                break ;
            case 1:
                imagegif($this->im, $this->srcImg);
                break ;
            default:
                $this->errorExit('源文件格式不对，必须是png,jpeg,gif格式，添加水印失败！');
                break;
        }
        //图片合成后的后续销毁处理 
        imagedestroy($this->im);
        imagedestroy($this->water_im);
    }
}
?>