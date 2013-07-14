zenglCMS 版权归www.zengl.com的站长zenglong所有。2012年made in china 。
将源码上传到服务器后，访问目录中的install.php即可进行cms的安装操作。

zenglcms网站的源码使用GPL v3的开源协议，协议具体内容请查看licence.txt文件。
更多信息请访问 www.zengl.com

以下是zenglcms的版本开发过程。

最后，需要注意的是本CMS的源代码都是在slackware linux下utf-8的语言环境下开发的，所以大部分文件都是utf-8的字符编码，采用的换行符是unix的换行符(\n)。所以如果查看源码出现乱码，请在相关工具中对编码等进行设置。

    v1.0.0正式版本，该版本修复了前面beta版本的bug,在主目录的php文件里的i_need_func函数依然使用调试模式（即最后一个参数使用true）。
    之所以这么改，是因为当最后一个参数不为true时，file_cache里生成的缓存在byethost主机测试时会有问题，所以依旧使用debug模式，该模式并不会影响速度。
    另外还修复了一个install.php报的数据库错误的BUG 。
    作者：zenglong
    时间：2012年7月9日

    这是v1.0.0的beta版本。
    该版本为前台显示添加了统一的头部和尾部的html代码，优化了首页的显示界面，同时在首页可以显示最近的更新，最新的评论信息，以及滚动显示最近的一些图片信息。
    在后台添加了系统配置管理单元，可以进行系统配置，利用phpfilemanager.php插件还可以进行在线编辑文件。
    另外还完善了install.php，install.php配合install目录中的代码可以进行CMS网站的安装操作。
    最后为源代码添加了gplv3的开源协议，主要的源代码的开头都有版权和开源协议的声明。
    其他的改动请用git log -p 或 gitk,msysgit等工具查看。
    
    作者：zenglong
    时间：2012年7月9日


     will commit 25 修复开启magic_quote_gpc时，文章评论显示异常的问题。


     will commit 25 针对byethost服务器上的问题作的些调整如，utf8表的创建，魔术斜杠的处理等。


     will commit 25 ,增加后台管理登录，栏目权重，删除静态页面的功能。commit25过度版本。


    v1.0.0第24个commit,该commit实现的CMS界面主题的设置，并且将网站前台的动态页面以html文件的形式静态化。
    
    当前默认的主题是default,由config.php里的$zengl_theme变量决定，所有default主题相关的模板和控制类文件都放在tpl/default目录中。所以如果要创建一个名为test的主题，可以在tpl目录中创建一个test目录，然后安装default目录的结构创建images,css,class目录，并创建该主题对应的模板文件，这样就有一个test主题了，只要在config.php中将$zengl_theme的值改为test,再更新下缓存和静态页面就可以查看效果了。config.php中的$zengl_old_theme变量用于存放当前主题之前的主题，例如如果切换到test主题，那么old_theme的值就是原来的default主题名，之所以添加这个变量是因为，当在test主题中找不到某个程式需要的模板时，就会自动到原来的default主题中寻找模板，这样在test中就不需要每个模板都写一遍，只要存放需要修改的模板文件即可。
    在default目录中images和css目录以及js目录里存放着模板文件需要的脚本，css样式表文件，和相关的图片。class目录中存放的是模板文件的控制类，通过该控制类加载模板，并替换掉模板里的标签，最后解析缓存就可以得到模板的输出页面了，采用的是MVC的模式。class目录中的控制类是由common_fun目录里对应的xx_class.php文件include加载进来的，例如common_fun里的article_class.php通过函数list_articles include加载当前主题目录中class目录里的list_articles_class.php文件再由该文件的程式加载对应的list_articles.tpl模板。
    经过该commit改造后，原来根目录里的css目录,js目录以及tpl目录里的模板都转移到default主题目录中了。根目录里的images目录还保留着，里面存放着默认的文章缩略图和ajax正在加载中的小图标，在某些地方会用到这两个图片。
    最后该commit版本完成了主页，文章列表页，文章页面的html静态化。通过article_class.php里的OneKeyHtml函数实现一键静态化的操作。静态化的原理其实就是先将动态页面里的超链接及其他相关的地方设为静态html页面的链接，再通过ob_start,ob_get_contents,以及ob_end_clean三个函数将输出的结果储存到对应的html文件中去，这样就实现了静态化了，评论的缓存文件也是用的这个原理。
    在config.php文件中$zengl_cms_full_domain变量的值是当前CMS网站的域名信息，该信息主要用于模板文件的<base>标签中，这样通过html里的<base>标签就可以将文章中的相对路径的图片信息转为绝对路径，这样就可以正确的显示出图片来。
    另外在config.php中还添加了个$zengl_cms_use_html变量，当该变量的值为yes时，就表示CMS网站使用的静态页面，那么即使用户访问php动态脚本的文章相关的页面也会自动转到html的静态页面(除非静态页面不存在)，如果在php脚本路径url后加入flagUsePHP=yes的参数的化也可以强制访问某动态页面。当该变量不为yes时，那么php页面就不会强制跳转到html页面，之所以在静态化时强制跳转，是因为当查询某tag标签对应的文章时，查询到的都是文章的php链接形式，那么如果不强制跳转，接下来的所有操作都会转为php脚本的操作，可能就会增加服务器的负担。
    
    其他的修改请用 git log -p 或 gitk之类的软件查看。
    
    作者：zenglong
    时间：2012年6月16日


    v1.0.0第23个commit,该commit实现了文章的描述信息(简介),缩略图,浏览计数功能，优化了评论回复的显示界面，将文章和评论的模板对应的控制类方法分离出去。
    
    在db_class.php中为数据库articles表结构添加了descript,smimgpath,scansCount三个字段，其中descript用于记录文章的描述信息(简介)，smimgpath用于记录文章的缩略图路径，scansCount用于记录文章的浏览次数。
    在archive_class.php附件类的upload方法中，对上传的附件，如果是图片就会调用ImageResize方法来生成缩略图，ImageResize方法的程式是从网上copy过来的，兼容性比较强。
    对article_class.php中的edit_article,show_edit_article和add_article,show_add_article方法做了修改，这样添加和编辑文章时就可以设置缩略图，描述信息及初始的浏览计数值。
    对show_article.tpl和list_articles.tpl模板做了修改，这样就可以在显示文章列表及显示某篇文章时，将文章的缩略图和描述信息及浏览计数信息等显示出来。
    config.php里添加了archive_smimg_width作为附件缩略图的宽度，archive_smimg_height为缩略图的高度，archive_smimg_default为默认的附件缩略图图标。listshow_article_smimg_default为当文章没有设置缩略图时，系统会默认显示的文章缩略图图标。archive_smimg_dirname为缩略图存放的文件夹名字。article_descript_charnum为描述信息的做大长度。
    重新设置了评论显示时使用的背景图片，界面比原来要好看些。
    在article_class.php中将list_articles,list_articles_ajax,index_articles,show_article这几个方法以及comment_class.php中的show方法里的内容,全部分离到common_fun目录的tpl_class目录的对应php文件中，因为这几个方法都是控制对应模板显示的，分离出来，以后方便生成主题包，或者方便替换为别的主题界面。
    
    其他的改动请用git log -p 或 gitk 等软件查看。
    
    作者：zenglong
    时间：2012年5月14日


    该commit为v1.0.0的第22_5个commit，是22号commit到下个23号commit的过度版本，该commit实现了ZENGLCMS系统的升级功能，通过测试，成功的将修改过的可用于升级的21号commit版本升级到了22_5号commit版本。
    
    在管理界面左侧菜单栏，添加了系统升级的超链接，该链接会跳转到新增加的update_operate.php文件来实现升级功能。
    在update_operate.php文件中，会先进行CMS_UPDATE权限判断，如果用户有权操作，且输入的升级时所需的用户密码和config.php中的cms_update_user,cms_update_pass一致，则执行升级操作。
    升级的过程其实就是将UpdateCms目录中的update.php文件copy到根目录，然后通过js脚本跳转到该update.php文件来执行具体的升级相关的操作。所以如果UpdateCms_commit22.tar.gz为升级包的话，只需要将该压缩包加压到CMS根目录(此时会自动生成所需的UpdateCms目录)，或者在本地解压，再将解压得到的UpdateCms目录上传到CMS根目录，最后在管理界面点击系统升级即可完成升级操作。
    UpdateCms里的update.php文件会执行具体的升级操作，不同的版本进行更新时，因为要更新的文件不一样，所以update.php的内容也不一样。但大概的模式差不多。
    在update.php里，为了安全，也会先进行权限和用户名密码的验证。所需要更新的文件名存放在$array_curdir_updatefile和$array_updatedir两个数组中，array_curdir_updatefile为CMS根目录下需要更新的文件，array_updatedir里的为其他目录中需要更新的文件。然后通过循环和copy函数将UpdateCms目录里需要更新的文件拷贝到CMS目录中，并将CMS里的原文件备份到UpdateCms里的bak目录中，所以如果升级失败，可以在bak目录中找到原文件，并手动恢复原文件。
    因为升级会覆盖原始文件，所以如果原始文件中作了某些改动的话，需要在bak目录中将原文件里的改动手动添加到更新后的文件中。
    接着update.php会通过update_set_config函数来更新config.php配置文件，该函数会先读取UpdateCms中的config.php的内容，再根据当前CMS里的配置，对config.php里的用户配置做修改，最后写入CMS的config.php中，这样更新完config.php后，原来的CMS配置信息可以保存下来。
    完成文件和配置的更新后，接下来update.php会先备份数据库，再用新的数据库结构来恢复数据库，这样数据库更新后，原来CMS中的数据可以保存下来。
    最后update会删除所有缓存，并更新用户权限。
    升级完后，只需刷新浏览器即可查看到效果。
    在该commit中删除了以前commit中的create_mysql_tables.php文件，将该文件中的更新权限的功能写入permission_class.php中，并将数据库的创建过程写入db_class.php文件中。这样就将这两个功能以类函数的形式封装起来。并创建了install.php用于以后的安装CMS的操作，在该install.php文件中通过调用permis类的update_permis来实现更新权限的功能，通过db_class中的create_db_tables函数来创建CMS的数据库结构。该install.php文件目前功能还不完善，等完善后，安装完CMS，就需要删除install.php文件，防止误操作。
    
    其他的改动请通过git log -p 或gitk等来查看
    
    作者：zenglong
    时间：2012年5月1日


    v1.0.0的第22个commit,该commit添加了评论的发表,回复,显示和评论管理功能。
    
    在create_mysql_tables.php中为mysql和sqlite数据库分别加入了comment和CommentReply两个数据库表的创建过程，comment用于存放评论信息，CommentReply则用于存放对评论的回复信息。
    comment_operate.php为评论功能的访问入口，将各种评论相关的请求参数转为comment_class.php里的comment类的方法。comment类中通过add,reply,show方法来实现评论的发表，回复以及显示功能。admin_comment_list和admin_reply_list用于在管理评论和回复时显示评论和回复的列表。del_comment,multidel_comments,del_reply,multidel_replys用于删除和批量删除评论及回复。
    在config.php配置文件中添加了$zengl_admin_comment_listnum和$zengl_admin_reply_listnum两个全局配置变量，用于设置管理评论和回复时，列表应当显示多少条评论和回复。配置文件中还添加了set_config函数用于对config.php里的变量进行设置，通过读取config.php里的内容，再用preg_replace正则表达式对相关变量的值进行替换，就可以修改config.php里的配置信息了，这样就不用直接操作config.php文件而修改配置。
    在comment_operate.php中就通过set_config函数来配置config.php里的zengl_admin_comment_listnum和zengl_admin_reply_listnum的值。这样就可以在管理界面直接进行配置。
    add_comment.tpl为发表评论时显示的界面模板，reply_comment.tpl为回复的界面模板，comment_success.tpl为发表评论或回复成功或失败时用于显示信息的模板，之所以用这个模板来显示信息，是因为该模板可以在评论回复发表完成后自动刷新评论显示页面，这样就可以看到发表的评论和回复了，就不用手动刷新界面。show_comment.tpl为评论和回复的显示界面模板。
    在显示某篇文章时，会在底部用iframe框架来显示评论和回复。该iframe会自动调整大小，使其不会显示出周围的边框，效果就和放在div里的效果差不多，而且可以很好的解决各种浏览器的兼容问题。admin_list_comments.tpl和admin_list_replys.tpl是管理评论回复时用于显示列表的模板。
    在这两个列表模板中加入了jquery.poshytip.min.js显示超链接的提示信息的jquery插件，以及jquery.tablesorter.min.js用于表格排序的插件。
    css里添加的文件为各个模板所需的样式表信息。
    另外还对jquery.tagsphere.js标签云的插件做了修改，这样在显示3D标签云时，通过点击标签云框架来启动和停止动画。这样可以减少多余的开销。
    其他的改动请通过git log -p 或 gitk图形程序来查看。
    
    作者：zenglong
    时间：2012年4月22日


    v1.0.0的第21个commit,该commit提供了数据库的备份和恢复功能(可以备份恢复mysql以及sqlite数据库，还可以将两个数据库里的数据相互转移),另外为文章添加了tag标签功能,还可以配置整个CMS的根目录及其他目录的名称。
    
    bak_restore_db.php为备份恢复数据库的请求处理文件，当没有设置用户名和密码时，该程序会加载db_restore.tpl模板来要求用户输入在config.php中设置的备份恢复时需要的用户名和密码，只有用户名和密码都正确时，才调用sql_class.php中的bak_tables方法和restore_tables方法来备份和恢复数据库，备份时根据请求的参数可以选择是备份为sqlite格式还是mysql格式，将表中的数据以insert的sql语句的格式储存到config配置的备份文件中，恢复时会先执行create_mysql_tables.php来删除原来的表，并创建新的表结构，再将备份文件中的insert的sql语句通过sql类的query方法来将数据插入到表中，从而更新表结构的同时可以恢复以前的数据。
    tags_class.php是文章标签类，专门用于文章标签的添加，更新，查询的操作。所有的文章标签都存放在数据库的tags表中，当添加和编辑文章时，通过tags类的add方法和update方法来将标签添加到tags数据库表中，或者更新某个tag记录里的文章id信息。
    当要显示某个文章时通过tags类的find方法在tags表中对表的articles列执行articles = id or articles like %,id,% or articles like %,id or articles like id,%的sql语句来得到该文章的tag列表。通过tags类的query方法可以得到某个tag标签对应的文章列表。通过getall和getall_ajax方法来得到所有的tag标签。
    在显示文章时，在文章的右边通过tags类的get_some方法可以得到按标签使用率排序的前几十个标签，并通过jquery的3D标签云插件将这些标签以动画方式显示出来，通过标签云下面的more链接可以弹出colorbox对话框，并显示出所有的其他标签。
    在config.php配置文件中添加了zengl_cms_version变量用于设置以后升级时所需的版本信息，db_database_bak_prefix和db_database_bak_suffix用于设置数据库的备份文件名的前缀和后缀。db_bak_pernum可以设置每个备份文件最多存放多少条数据记录。
    db_restore_user和db_restore_pass用于数据库的备份和恢复时输入的用户名和密码。
    zengl_cms_tpl_dir和zengl_cms_rootdir可以设置模板文件和模板缓存文件的存放路径，以及设置CMS的根目录路径。file_func.php中的zengl_cms_filecache_dir用于设置程序文件的缓存文件的路径。
    
    作者：zenglong
    时间：2012年4月1日


    v1.0.0第20个commit,该commit将文章的编辑删除等功能转移到admin管理界面。
    
    add_edit_del_show_list_article.php添加了和文章管理相关的请求的处理，并将请求转到article类的admin_list和admin_multi_del_move等方法中。
    在article_class.php中通过admin_list方法将要编辑的文章列表显示出来，admin_multi_del_move方法可以批量的处理文章的编辑和删除的操作。其中使用了admin_list_article.tpl以及admin_list_article_ajax.tpl(ajax传输时用的),作为管理文章时显示的模板。并且在脚本方面使用了jquery.simplemodal.1.4.2.min.js作为弹出对话框的插件。
    其他的改动可以用git log -p来查看。
    
    作者：zenglong
    时间：2012年3月22日


    v1.0.0的第19个commit,该commit优化了界面，同时添加了字符串截取函数。
    
    通过修改login.tpl和register.tpl以及相关的css文件，为登录注册页面增加了返回来页的超链接，这样可以选择是返回之前的页面还是继续登录注册等操作。
    文章内容页面和列表页面的超链接采用黑色和红色为背景色，白色为前景色以增强视觉效果，并且取消了这两个页面和主页的超链接的下划线。
    将管理界面的div采用百分比作为宽，iframe使用100%为宽，这样在IE下右边的div就不会跑到左边的div的下边了。
    在help_func.php中添加了$help_global_referer变量，这样在登录注册页面获取返回来页的url时就可以直接从该变量中得到，不用去cookie中获取url,因为此时的cookie中保存的还是上一次的url信息，还没有得到更新。
    在help_func.php中还添加了subUTF8函数，该函数从网上某博客采集过来的，用于截取UTF8中英文混合字符串指定的长度，该长度以ascii字符长度为准，即一个中文对应两个长度单位，一个英文对应一个长度单位，对网上的该函数做了些改动，采用mb_strlen和mb_substr这样可以防止乱码发生。该函数目前用于主页和文章列表页面，当标题超过div等容器的宽时用省略号代替。
    
    作者:zenglong
    时间：2012年3月3日


    v1.0.0第18个commit,该commit增加了管理界面，同时优化了文章内容界面,及登录和信息反馈的界面，并且将各种操作通过超链接连接起来。
    
    .htaccess文件中增加了DirectoryIndex指令，这样在apache服务器中，当访问根目录时自动定位到index.php首页。
    admin.php为网站管理界面的入口文件，该文件通过调用admin_class.php中admin类的相关函数来执行对应的操作。
    admin_class.php通过show函数将admin.tpl管理界面的模板输出显示出来，admin.tpl中通过iframe大致将页面分为两块，一块为选择要执行的管理操作的菜单部分，另一块为具体的管理程式的显示界面，有点类似dedecms的管理界面，大部分CMS也是类似的管理布局。有了admin管理界面后，就不用像以前那样通过在地址栏手动输入管理程式的网址和参数来执行管理了，可以直接通过超链接跳到相应页面。
    因为增加了新的admin管理类，所以在file_func.php中对该类进行了注册，这样就可以在admin.php中调用i_need_func来加载admin类了。
    admin管理界面是一个通用的管理界面，即超级管理员和普通管理员都在这个页面中进行管理，不像dedecms有超级管理员界面还有普通会员界面。admin类会通过权限来判断用户可以执行哪些操作，并只显示有权限执行的操作。在permission_class.php中增加了ADMIN_SHOW权限来判断用户是否可以进入管理界面。
    除了添加管理界面外，还对网站的一些界面做了些优化和美化操作。如：文章内容页面通过修改show_article.tpl，以及增加show_article.css及修改article_class.php等文件使其内容可以显示在一个卷轴背景中，让画面更美观，同时通过对section_class.php栏目类的修改，在网页的顶部导航栏中增加了主页，这样可以直接跳转到主页。
    通过对error.tpl，success.tpl以及login.tpl的修改，并增加对应的css文件，使错误等信息的显示页面和登录页面也显示在一个卷轴背景中，画面得到美化，另外，通过在error.tpl和success.tpl文件中增加jQuery.timers.js，jquery的定时器插件，从而增加倒计时功能，倒计时到1时，画面会自动跳转，具体跳转的页面由help_func.php中增加的get_jmp_locs函数来决定，help_func.php中还增加了help_setcookie_pre_url及help_get_pre_url两个辅助函数，这两个函数可以将用户跳转之前的页面保留到cookie中，这样跳转时就可以根据cookie信息跳转到之前的页面，例如，login_out_register.php就在登录界面显示之前将进入登录前的页面通过help_setcookie_pre_url函数保存到cookie中，这样，登录后，页面就会自动跳到登录前的页面。
    此外，还修复了archive_class.php附件操作的BUG，之前commit中附件编辑时，如果上传了一个名字不一样的附件时，原来的附件路径名就会被修改掉，从而导致添加了该附件的文章会找不到原来的附件。该commit如果进行附件的编辑操作，附件在服务器的路径名不会被改动，只改动数据库里的名字和时间信息，从而避免了以前的BUG。
    其他的改动请通过git log -p 来查看。
    
    作者：zenglong
    时间：2012年2月25日


    v1.0.0第17个commit,该commit添加了首页，还对文章列表模板的浏览器兼容性做了重新布局。
    
    index.php首页程序通过调用article_class.php的index_articles方法来生成首页。在article_class.php与首页相关的函数中最重要的是index_articles_divs方法，该方法将每个栏目的文章按栏目名分为几组，并按div的float方式排列起来，这样就可以浏览大部分栏目的最新文章信息。每篇文章的名字超出div边界时，通过mb_substr截取UTF-8字符串，并将多余部分用省略号显示出来。
    css/index.css为首页index.tpl模板的样式文件。
    list_articles.tpl和list_articles_ajax.tpl为文章列表模板，在此对这两个模板做了修改，原来的显示方式在不同浏览器和不同分辨率下的显示效果差别较大，所以将li标签改为span标签，采用div的背景和填充模式，再加上list_article.css样式文件，就基本上在不同浏览器和不同分辨率下可以有相同的显示效果了。
    
    作者：zenglong
    时间：2012年2月10日


    v1.0.0第16个commit,改进了文章列表的显示页面,利用jquery及其插件以及ajax技术,给显示页面添加了多级导航菜单和分页功能。
    
    add_edit_del_show_list_article.php中添加对listajax参数的处理,通过调用article类中的list_articles_ajax方法,来处理点击分页时的ajax请求,这样点击分页号就可以异步传输文章列表信息,实现无刷新技术。
    common_fun/article_class.php中添加了list_articles_ajax方法用于处理分页ajax请求，同时修改了list_articles方法，用于设置修改后的模板所需的信息。section_class.php中添加了article_class中需要调用的recur_show_secs方法，该方法将栏目信息按照ul li的html标签模式echo出来，在模板文件里会通过jquery插件superfish将ul标签显示为导航菜单。
    sql_class.php中在数据库类里添加了get_num方法和对应的rownum成员,在get_num方法中可以将查询的结果集的总记录数获取并储存到rownum中。
    css目录中存放的都是模板文件所需的css样式文件。list_article.css为自己添加了用于list_article.tpl的css,里面的样式主要是为文章列表设置需要的背景图片。style.css为jpaginate分页插件需要的css。superfish开头的3个css文件为导航菜单插件superfish需要的css。
    js目录中存放的是模板所需的js脚本文件。superfish.js和hoverIntent.js为导航插件superfish的脚本文件。jquery-1.7.1.js为目前较新的jquery主程序脚本。jquery.paginate.js为分页插件的脚本，这里对这个插件作了些改动，将原111行位置处的_ulwrapdiv和_divwrapright的width的值outsidewidth都加了100，这样分页插件在IE6中就不会出现显示分页号太窄的情况。
    list_articles.tpl中加入了两个插件所需的脚本，并通过jquery脚本将文章列表以需要的数目如每7个文章显示到一个背景图片中，美化了外观。
    list_articles_ajax.tpl为article_class.php中list_articles_ajax方法需要调用的模板，用于显示输出对应ajax请求的处理结果。
    
    作者：zenglong
    时间：2012年2月9日


    该commit为v1.0.0的第15个commit，该commit添加了附件的编辑和删除功能。
    
    在archive_class.php附件类中增加了附件的编辑和删除功能，在permission_class中增加了相应的和附件编辑，删除相关的权限定义，list_upload_archive.php中也增加了编辑删除的权限判断以及调用对应的类方法来处理编辑删除的操作。
    edit_archive.tpl是编辑附件时用于显示的模板文件，目前可以编辑某个附件的名字，可以重新上传附件对应的文件，并修改对应的上传时间。list_uploads.tpl列举附件的模板里为每个附件都添加了编辑和删除的链接。
    
    作者：zenglong
    时间：2012年1月30日


    该commit为v1.0.0的第14个commit，该commit按照文章的编程模式重写了上传浏览附件的功能，并将附件记录到数据库中。
    
    将附件上传和浏览的功能分离为MVC的模式，处理前台为list_upload_archive.php文件，common_fun/archive_class.php为附件处理的类文件，list_upload_archive.php接收参数，并调用类文件里的相关类的方法进行处理。还是采用原来的list_uploads.tpl为浏览附件的模板文件。
    同时将archive_class中的archive类在file_func.php中进行了注册。permission_class.php中增加了附件的上传和浏览的权限定义。在config.php中设置了zengl_upload_dir作为附件上传的位置，可以根据需要修改。
    create_mysql_tables.php中增加了附件相关的数据库表，在上传附件的同时将附件信息记录在数据库中，方便管理，浏览附件时就可以直接从数据库中读取相应用户的附件信息。
    write_article.tpl模板文件中将CKEDITOR编辑器的上传和浏览URL改为list_upload_archive.php对应的路径和参数。同时还增加了CKEDITOR.config.tabSpace=10的配置，这样用户就可以在CKEDITOR编辑器中输入tab键了。
    在根目录和upload目录中加入了.htaccess文件，防止通过浏览器直接枚举列出目录中的文件信息。
    该commit还修复了article_class.php文章类里的show_article时模板缓存的BUG，以及list_articles时链接没修正的BUG，具体可使用git log -p来查看所做的修复。
    
    作者：zenglong
    时间：2012年1月28日


    该commit为v1.0.0的第13个commit,该commit添加了栏目编辑功能，并且在栏目管理中引入了权限控制机制。
    
    add_del_edit_section.php采用了和add_edit_del_show_list_article.php一样的结构，将添加，删除，编辑栏目功能整合到一起，根据不同的参数对用户的权限和参数的有效性进行检查。并最终调用section_class.php里的add,del,edit方法来实现。
    help_func.php是将php数组转化为javascript的数组的辅助函数库。并且在file_func中进行了注册。通过将栏目缓存数组转为js数组，这样在编辑栏目时，当选择一个要编辑的栏目时，所属栏目自动切换到该栏目的父栏目，方便用户操作。
    permission_class.php暂时先打开注册用户的栏目管理功能，不过，注册用户只能管理自己创建的栏目，无法修改系统管理员或别的用户创建的栏目。
    section_class.php中添加了edit栏目编辑功能，编辑栏目可以进行栏目的重命名操作，还可以将某个栏目及其子栏目移动到其他栏目下。
    create_mysql_tables.php为栏目section表增加了permis权限字段，这样就可以对栏目的操作权限进行管理。
    del_section.tpl是编辑和删除栏目时采用的模板文件，该界面既可以用于删除栏目操作，也可以用于编辑栏目，有相关的checkbox供选择来切换操作。
    
    作者：zenglong
    时间：2012年1月11日


    这是v1.0.0的第12个commit,该commit将文章的添加,编辑,删除,显示,列表都合并到一个文件中。
    
    该commit将del_article.php,list_article.php,show_article.php,update_article.php,write_article.php都删除了，都统一到add_edit_del_show_list_article.php中。user_class.php中注册时添加了会判断是否有已存在的用户。
    其他的文件的修改可以通过git log -p查看。
    
    作者：zenglong
    时间：2012年1月8日


    这是v1.0.0的第11个commit,该commit增加了权限管理和缓存清理功能。
    
    clear_filecache.php是清除缓存的主文件，通过权限和参数判断用户是否可以删除缓存文件，可以的情况下调用cache_class里的缓存类的clear_caches方法来清除缓存，permission_class.php是权限管理相关的类，通过权限数组判断用户是否有权进行相关的操作。write_article.php和clear_filecache中都使用了该类来进行权限的判断。
    article_class中增加了permis成员，这样就可以调用权限类的方法进行权限判断。clear_filecache.tpl是清除缓存时用于显示删除了哪些缓存文件用的模板文件。file_func.php里增加了对permis和cache类的声明，这样就可以在i_need_func中调用了。
    session_class.php中增加了userPermis和levelPermis两个会话成员，这样就可以查看用户的权限和所在组的权限了。
    user_class.php里在用户登录时从数据库里还读取出用户权限和组权限保存到session中，以后就可以直接session查看权限了。
    create_mysql_tables.php在mysql和sqlite中增加了用户组的表，同时增加了和权限有关的字段。
    
    其他文件所做的修改可以通过git log -p来查看所做的修改。
    
    作者：zenglong
    时间：2012年1月8日


    该commit是v1.0.0的第10个commit,该commit增加了栏目删除功能。
    
    将以前版本的add_section.php改名为add_del_edit_section.php,将栏目的添加删除编辑放在一个程序里，和登录、注册、注销一样。删除栏目的操作除了在section表中将该栏目删除外，还将该栏目的文章转到目标栏目下，并且可以根据需要选择是将子栏目转到目标栏目下还是将子栏目递归删除掉，如果递归删除子栏目则子栏目的文章也转到目标栏目下。
    另外还将该栏目的父栏目的sec_content里关于该栏目的记录删掉，这里用到了正则表达式来删除。
    section_class.php中增加了和删除有关的函数。
    del_section.tpl为删除操作对应的模板文件。
    
    作者：zenglong
    时间：2011年12月28日


    v1.0.0第9个commit,该commit将所有栏目作为数组序列化输出到文件，减少数据库读写次数，同时在文章显示部分增加了所属栏目的显示功能。
    
    article_class.php中将getallsections函数做了处理，当不存在栏目缓存文件时，才从数据库读取栏目信息，再将栏目数组序列化输出到文件中，以后直接从缓存文件中读取并反序列化栏目数组，还添加了show_article_sections函数通过递归调用本函数将文章的栏目和父栏目都显示出来。在tpl/show_article.tpl里添加了栏目模板。show_article.php中采用调试模式生成类的缓存文件。
    section_class.php中同样将栏目数组序列化到文件来减少数据库的读写次数。
    栏目数组的缓存文件为file_cache/all_sections_array.php文件。
    
    作者：zenglong
    时间：2011年12月26日


    v1.0.0第8个commit，该commit增加了选择栏目时递归显示子栏目的功能。
    
    在修改的各个文件中加入了递归选项，当需要递归时，将子栏目中的文章通过递归函数显示出来。acticle_class.php将各栏目ID在sql语句中通过or连接起来，这样就可以同时查询本栏目和子栏目的文章了，section_class.php对list_sections进行处理，在有$all的情况下就不getall了，就不会反复获取所有栏目的信息。list_articles.tpl中加入了是否显示子栏目的复选框，还通过jquery的change事件，当选择复选框时则显示子栏目。list_sections.tpl中的select组件的change事件转到list_articles.tpl中通过jquery来实现。
    
    作者：zenglong
    时间：2011年12月23日


    v1.0.0第7个commit，该commit将栏目和文章结合起来，将文章都归类到不同的栏目中。
    
    common_fun/article_class.php，list_articles.php，tpl/list_articles.tpl中都加入了栏目的显示功能。
    file_func.php中加入了article类对section栏目类的依赖，common_fun/section_class.php中对递归显示栏目做了些处理，使栏目显示呈目录树结构。common_fun/user_class.php将注册时的管理员权限恢复为普通注册用户权限。
    create_mysql_tables.php在文章表中加入了栏目ID，在用户表初始化时加入了root管理员，初始密码为admin，用于调试栏目。
    list_sections.tpl模板里加入了目录树结构支持，write_article.tpl添加文章模板里加入了栏目显示功能。write_article.php加入了文章栏目ID添加功能。
    
    作者：zenglong
    时间：2011年12月22日


    该commit为v1.0.0的第6个commit，该commit增加了栏目的添加功能。
    
    add_section.php为栏目添加的主文件，该文件通过调用common_fun/section_class.php中的section类来实现具体的栏目添加和显示功能。section类已在file_func.php中注册了。用户类中暂时修改了注册算法，将注册用户提为管理员，主要为了方便测试，添加栏目的操作只有管理员可以操作，在以后会改回来。create_mysql_tables.php中添加了栏目相关的数据库表，tpl/add_section.tpl为添加栏目的模板文件。
    
    作者：zenglong
    时间：2011年12月21日


    v1.0.0第5个commit,该commit改进了include方法，用i_need_func来动态导入各个类，给每个类起了个别名，方便加载。同时将文章操作对象化。
    
    common_fun/article_class.php是文章操作相关的类，将添加，删除，修改，显示的操作对象化，方法化。file_func.php提供i_need_func方法，自动查找各个类的依赖，并自动加载。del_article.php和list_article.php,show_article.php,update_article.php,write_article.php分别都采用了article类中的方法，使得程序流程更接近自然语言，程序结构更明朗，方便维护。login_out_register.php也采用了i_need_func方法来加载所需的类。
    
    作者：zenglong
    时间：2011年12月18日


    这是v1.0.0第4个commit,该commit在数据库中加入了sqlite轻量级数据库的支持，并将大部分文件做了面向对象化的处理。
    
    comm_fun/file_func.php是新增的，用于将多个需要的文件一次性include进来，通过将这些文件输出到file_cache的一个缓存文件中，这样就只需要include单个文件，并且在发布的时候可以进行压缩处理。common_fun/sql_class.php是和数据库操作相关的类文件，这次加入了sqlite的支持。
    common_fun/user_class.php中根据file_func和sql_class的改变做了相应的改动。
    config.php中加入了sqlite数据库的定义。
    create_mysql_tables.php中也加入了sqlite数据库的创建部分。
    write_article.php,update_article.php,del_article.php都改用了sql_class里的类来处理数据库部分，同时加入了用户权限控制，只有授权用户才能进行相关的文章操作。
    list_articles.php，show_article.php也改用了sql_class。
    login_out_register.php和register.tpl中对注册部分加入了验证码功能。
    
    作者：zenglong
    时间：2011年12月17日


    在该commit中加入了用户的登录，注销，注册即login,logout,register的功能，还将数据库之类的做了些面向对象化的处理。
    
    auth_class.php用于图形验证码的处理类，里面使用gd库和随机函数类生成png图形。
    error_class.php做了些修改，增加了成功时的输出显示部分。session_class.php是和session用户会话跟踪相关的类，sql_class.php是数据库面向对象化的处理，user_class.php是处理用户登录，注销，注册的核心类。
    config.php中加入了随机的密码掩码，该掩码会加到用户密码后面，用于md5生成hash密码用的。create_mysql_tables.php作了修改，采用了sql类，使得数据库的创建更加对象化。list_articles.php也做了些修改，加入了用户登录后的显示处理。
    login_out_register.php该文件处理用户的请求，并调用user类中的方法处理登录，注册，注册，还有调用auth类来生成验证码。
    tpl里对应的修改了list_articles.tpl来和相应的php文件相匹配。tpl/login.tpl是显示登录界面的模板文件，register.tpl是注册界面的模板文件，success.tpl是error_class.php中success类对应的成功时显示的模板。
    
    作者：zenglong
    时间：2011年12月16日


    该commit完成了文章的添加，删除，修改的所有功能，以及上传附件，列举附件的功能，可以很好的和CKEDITOR编辑器配合使用。
    
    在common_fun中有两个和模板相关的新文件，其中error_class.php是和错误显示相关的模板处理类，而tpl_class.php则是通用的模板处理类，通过这两个类可以让模板的输出显示对象化，条理更清晰！del_article.php用于删除文章的操作，list_articles.php用于列举文章列表，也是目前暂时的首页。list_uploads.php的创建初衷是因为CKEDITOR编辑器要用到文件浏览功能。show_article.php用于具体显示某个文章的内容，tpl里的error.tpl是错误输出时用的模板文件，在tpl里这次修改了list_article.tpl模板，list_uploads.tpl是显示附件列表的模板，show_article.tpl是显示文章内容的模板，write_article.tpl是添加和编辑文章的模板，在上个commit中没有该模板，直接在php里加入的html，现在加了模板来实现代码和界面的分开，update_article.php用于编辑并更新文章，也使用的是write_article.tpl模板。upload_file.php用于上传文件的处理，也是和CKEDITOR上传功能相对应的。这里修改了write_article.php，使其可以使用write_article.tpl模板来输出内容！
    
    作者：zenglong
    时间：2011年12月15日


    这是我的1.0.0版本的第一个commit
    
    在该commit中，create_mysql_tables.php用于创建数据库中的表结构，config.php中则含有数据库等的配置信息。common_fun里面用于存放一些常用的函数，比如目前使用中的connect_db.php，直接包含该文件可以简化数据库的连接操作，以后会加入数据库对象来让数据库的连接对象化。write_article.php用于提交发表文章，list_articles.php用于显示文章列表，该程序中使用了最简单的模板实现方式，采用str_replace来替换模板标签为对应的php标签。tpl里存放的是模板文件，和模板的缓存php文件。
    
    作者：zenglong
    创建时间：2011年12月14日
