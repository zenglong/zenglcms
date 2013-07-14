<table>
	<tr align='center'><th>文章ID</th><th>选择</th><th>文章标题</th><th>所属栏目</th><th>时间</th><th>状态</th><th>操作</th></tr>
	{zengl for_articles}
	<tr>
		<td align='center' >{zengl article_id}</td>
		<td align='center'><input type="checkbox" class="checkID" value="{zengl article_id}"></td>
		<td><a class = 'td_title' href={zengl article_loc} target='_blank' title='{zengl article_tip}'>{zengl article_title}</a></td> 
		<td>{zengl sec_name} </td>
		<td> {zengl article_time} </td>
		<td> {zengl article_html_status} </td>
		<td> 
			<a href={zengl article_edit}>编辑</a>
			<a href={zengl article_del}>删除</a> 
		</td>
	</tr>
	{zengl endfor}
</table>