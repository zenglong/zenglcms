(function(){
    //Section 1 : 按下自定义按钮时执行的代码
    var a= {
        exec:function(editor){
            //alert("这是自定义按钮");
			editor.insertHtml(
'<table align="center" border="0" cellpadding="6" cellspacing="0" style="BORDER-RIGHT: rgb(204,204,204) 1px dotted; TABLE-LAYOUT: fixed; BORDER-TOP: rgb(204,204,204) 1px dotted; BORDER-LEFT: rgb(204,204,204) 1px dotted; BORDER-BOTTOM: rgb(204,204,204) 1px dotted" width="95%"><tbody><tr><td bgcolor="#fdfddf" style="WORD-WRAP: break-word"></td></tr></tbody></table>');
        }
    },
    //Section 2 : 创建自定义按钮、绑定方法
    b='zengl_codetable';
    CKEDITOR.plugins.add(b,{
        init:function(editor){
            editor.addCommand(b,a);
            editor.ui.addButton('zengl_codetable',{
                label:'zengl 代码表格,可以在其中放一些code代码,不过需要自己进行语法高亮',
                icon: this.path + 'images/codetable.gif',
                command:b
            });
        }
    });
})();