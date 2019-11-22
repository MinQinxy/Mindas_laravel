<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="/storage/img/favicon.png">
    <title>Mindas | Write</title>
    <link rel="stylesheet" type="text/css" href="/storage/lib/editor.md-master/css/editormd.css" />
    <link rel="stylesheet" type="text/css" href="/storage/lib/bootstrap-4.3.1-dist/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/storage/lib/jquery-confirm/jquery-confirm.min.css" />
    <link rel="stylesheet" type="text/css" href="/storage/css/style.css" />
</head>

<body>
    <div id="main" class="input-group mb-3">
        <div class="input-group-prepend htitle" >
            <span class="input-group-text">标题：</span>
        </div>
        <input id="title" type="text" class="form-control htitle" placeholder="Title" />
        <div class="input-group-append htitle">
            <button id="sub" class="btn btn-info" type="submit" onclick="save()">发表</button>
        </div>
        <div id="mindas_markdown" ></div>
    </div>
    <script src="/storage/js/jquery.min.js"></script><!-- jquery引入 -->
    <script src="/storage/lib/bootstrap-4.3.1-dist/js/bootstrap.bundle.js"></script><!-- bootstrap框架js引入 -->
    <script src="/storage/lib/editor.md-master/editormd.js"></script><!-- editormd插件引入 -->
    <script src="/storage/lib/jquery-confirm/jquery-confirm.min.js"></script><!-- jquery-confirm插件引入 -->
    <script type="text/javascript">
    var testEditor;
    $(function() {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        testEditor = editormd("mindas_markdown", {
            width: "100%",
            height: "95%",
            path: '/storage/lib/editor.md-master/lib/',
            theme: "default",
            previewTheme: "default",
            editorTheme: "elegant",
            markdown: "",
            codeFold: true,
            syncScrolling : "single",
            saveHTMLToTextarea: true, // 保存 HTML 到 Textarea
            searchReplace: true,
            //watch : false,                // 关闭实时预览
            htmlDecode: "style,script,iframe|on*", // 开启 HTML 标签解析，为了安全性，默认不开启
            //toolbar  : false,             //关闭工具栏
            //previewCodeHighlight : false, // 关闭预览 HTML 的代码块高亮，默认开启
            emoji: true,
            taskList: true,
            tocm: true, // Using [TOCM]
            tex: true, // 开启科学公式TeX语言支持，默认关闭
            flowChart: true, // 开启流程图支持，默认关闭
            sequenceDiagram: true, // 开启时序/序列图支持，默认关闭,
            //dialogLockScreen : false,   // 设置弹出层对话框不锁屏，全局通用，默认为true
            //dialogShowMask : false,     // 设置弹出层对话框显示透明遮罩层，全局通用，默认为true
            //dialogDraggable : false,    // 设置弹出层对话框不可拖动，全局通用，默认为true
            //dialogMaskOpacity : 0.4,    // 设置透明遮罩层的透明度，全局通用，默认值为0.1
            //dialogMaskBgColor : "#000", // 设置透明遮罩层的背景颜色，全局通用，默认为#fff
            imageUpload: true,
            imageFormats: ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
            imageUploadURL: "/blog/uploadimg.html", //处理图片上传的后端程序
            onload: function() {

            }
        });



    });



    function save() {

        var title = $("#title").val();

        if (title.trim() == "") {
        	console.log("here");

        	$.alert({
                title: '错误',
                content: "标题不能为空！"
            });

        } else {

            var content = testEditor.getMarkdown();

            var data = {
                'title': title,
                'content': content
            };
            //for debug
            console.log(content);

            //确认传输？
            $.confirm({
                title: '确认发表？',
                content: "标题：'" + $('#title').val() + "'?",
                buttons: {
                    "确认": function() {
                        $.ajax({
                            url: "/blog/submit.html",
                            type: "POST",
                            data: data,
                            success: function(data, status) {
                                if (data.state == 1) {
                                    $.alert({
                						title: '成功',
                						content: "发表 '"+ title +"' 成功"
            						});
                                } else {
                                    $.alert({
                						title: '失败',
                						content: "发表失败！"
            						});
                                }
                            },
                            error: function(req, status, e) {
								$.alert({
                					title: '错误',
                					content: "上传错误！"
            					});
            					console.log(req);
                            },
                            dataType: "json"
                        });
                    },
                    "取消": function() {

                    }
                }
            });



        }

    }
    </script>
</body>

</html>
