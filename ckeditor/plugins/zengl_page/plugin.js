(function(){
    //Section 1 : 按下自定义按钮时执行的代码
    var a= {
        exec:function(editor){
            //alert("这是自定义按钮");
			editor.insertHtml('[zengl pagebreak]');
        }
    },
    //Section 2 : 创建自定义按钮、绑定方法
    b='zengl_page';
    CKEDITOR.plugins.add(b,{
        init:function(editor){
            editor.addCommand(b,a);
            editor.ui.addButton('zengl_page',{
                label:'zengl 分页符',
                icon: this.path + 'images/pagebreak.gif',
                command:b
            });
        }
    });
})();