<?php 
global $zengl_cms_rootdir;
global $archive_smimg_default;
global $rvar_sec_page;
$page_size = 20; //每页显示20个缩略图
if(isset($rvar_sec_page))
	$startPage = ($rvar_sec_page - 1) * $page_size;
else
{
	$rvar_sec_page = 1;
	$startPage = 0;
}
if(!isset($this->sql))
	$this->sql = new sql('utf8');
else
	$sql = &$this->sql;
$sql->query("select * from {$sql->tables_prefix}archives where userID = {$this->session->userID}
order by time desc");
$sql->get_num(); //先得到总数
$pageNum = ceil($sql->rownum/$page_size); //利用总数得到总页数
$sql->query("select * from {$sql->tables_prefix}archives where userID = {$this->session->userID}
order by time desc limit $startPage,$page_size"); //用limit查询分页记录
$title = "附件目录列表：";
$filetpl = $zengl_cms_tpl_dir . $zengl_theme . '/list_uploads.tpl';
$filecache = $zengl_cms_tpl_dir . 'cache/list_uploads_cache.php';

if(!file_exists($filetpl))
	die('tpl file '.$filetpl.' does not exist!');

if(file_exists($filecache) && ( filemtime($filecache) > filemtime($filetpl) ) &&
	(filemtime($filecache) > filemtime($zengl_cms_tpl_dir . 'filetpl')) )
	include $filecache;
else
{
	$upload_num = 0;
	$tpl = new tpl($filetpl, $filecache);
	$tpl->setVar('theme', 'echo $zengl_cms_tpl_dir . $zengl_theme',true);
	$tpl->setVar('title', $title);
	$tpl->setVar('rootdir', 'echo $zengl_cms_rootdir;',true);
	$tpl->setVar('for_uploads',
			'<?php
			while ($sql->parse_results()) {
				$upload_num++;
			?>');
	$tpl->setVar('upload_num','echo ($upload_num+$startPage);',true);
	$tpl->setVar('smimgsrc', '
			if($sql->row["smimgpath"] != "")
			echo $sql->row["smimgpath"];
			else
			echo $archive_smimg_default;',true);
	$tpl->setVar('upload_loc', '"<?php echo $sql->row["path"]; ?>"');
	$tpl->setVar('upload_file',
			'<?php
			if(file_exists($sql->row["path"]))
			echo $sql->row["title"];
			else
			echo $sql->row["title"] . " (该附件已经被删除或转移!)";
			?>');
	$tpl->setVar('upload_time', '<?php echo "上传时间：" . date("Y/n/j G:i:s",$sql->row["time"]); ?>');
	$tpl->setVar('upload_size',
			'<?php
			echo number_format(filesize($sql->row["path"])/1024,3);
			?>');
	$tpl->setVar('archive_edit', '"'.$zengl_cms_rootdir.'list_upload_archive.php?action=edit'.
			'&archiveID=<?php echo $sql->row["archive_ID"] ?>"');
	$tpl->setVar('archive_del', '"'.$zengl_cms_rootdir.'list_upload_archive.php?action=del'.
			'&archiveID=<?php echo $sql->row["archive_ID"] ?>"');
	$tpl->setVar('endfor', '<?php } ?>');
	$tpl->setVar('for_pages', 'for ($i=1;$i<=$pageNum;$i++) {',true);
	$tpl->setVar('page_val', 'echo $i',true);
	$tpl->setVar('page_option', '
		if($i == $rvar_sec_page)
		echo \'selected="selected"\';
		else
		echo "";',true);
	$tpl->cache();
	include $filecache;
}
?>