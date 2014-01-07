define(['jquery', 'jquery-form', 'loadJSON'], function(){
    // 渲染富文本编辑器
    window.ueditors = [];
    $.fn.ueditor = function() {
        var id = $(this).attr('id');
        var editor = new UE.ui.Editor();
        editor.render(id);

        // 记录到全局供其他方法使用
        window.ueditors.push(editor);

        return this;
    };

    // 从全局获取或重新渲染一个编辑器
    $.getUeditor = function() {
        if (!window.ueditors.length) {
            $('<textarea id="ueditor-image-picker" class="hide"></textarea>').appendTo('body').ueditor();
        }
        return window.ueditors[0];
    };

    // 点击弹出ueditor的图片选择器
    $.fn.imageInput = function() {
        var editor = $.getUeditor();
        var $this = $(this);
        $this.next().click(function () {
            var dialog = editor.getDialog("insertimage");
            var callback = function (type, imgObjs) {
                var src;
                if (imgObjs.src) {
                    src = imgObjs.src;
                } else {
                    src = imgObjs[0].src
                }
                $this.val(src);
                editor.removeListener("beforeInsertImage", callback);
                return false;
            };
            dialog.open();
            editor.addListener("beforeInsertImage", callback)
        })
    };

    // 点击弹出多图片选择器
    $.fn.imagePicker = function(images) {
        var $this = $(this);
        require(['template'], function() {
            // TODO 如何分离
            var tmpl = '<li>'
                + '<a href="<%= src %>" data-rel="colorbox" class="cboxElement">'
                + '  <img src="<%= src %>">'
                + '</a>'
                + '<div class="tools tools-bottom">'
                + '  <a href="javascript:;">'
                + '    <i class="icon-remove red"></i>'
                + '  </a>'
                + '</div>'
                + '<input type="hidden" name="images[]" value="<%= src %>">'
                + '</li>';

            var selectBtn = $this.find('.select-image');

            var editor = $.getUeditor();

            // 1. 渲染已有的图片
            for (var i in images) {
                var data = {
                    src: images[i]
                };
                var img = $(template.compile(tmpl)(data));
                img.insertBefore(selectBtn);
            }

            // 2. 点击选择图片
            selectBtn.click(function () {
                var dialog = editor.getDialog('insertimage');
                var callback = function (type, imgObjs) {
                    if (undefined == imgObjs[0]) {
                        imgObjs = [imgObjs];
                    }
                    for (var i in imgObjs) {
                        var img = $(template.compile(tmpl)(imgObjs[i]));
                        img.insertBefore(selectBtn);
                    }
                    editor.removeListener('beforeInsertImage', callback);
                    return false
                };
                dialog.open();
                editor.addListener('beforeInsertImage', callback)
            });

            // 3. 点击删除按钮,移除整个图片
            $this.delegate('.icon-remove', 'click', function(){
                var parent = $(this).parents('li:first');
                parent.fadeOut(function(){
                    parent.remove();
                });
            });
        });
    };
});