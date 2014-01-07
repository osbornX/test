(function($){
    // 为表单增加更新事件(常用于列表顶部搜索表单,和添加编辑表单)
    $.fn.update = function (fn) {
        this.find('select').change.call(this, fn);
        this.find('input').keyup.call(this, fn);
        return this;
    };

    $.fn.loadParams = function() {
        return this.loadJSON($.deparam.querystring());
    };

    $.appendUrl = function (url, params) {
        url = url + (-1 == url.indexOf('?') ? '?' : '&');
        switch (typeof params) {
            case 'string' :
                return url + params;
            case 'undefined' :
                return url;
            default:
                return url + $.param(params);
        }
    };

    $.url = function (url, params) {
        return config.baseUrl + $.appendUrl(url, params);
    };

    $.queryUrl = function(url) {
        return $.url(url, $.deparam.querystring());
    };

    $.tip = function (message, type, delay) {
        return $.bootstrapGrowl(message, {
            type: type,
            offset: {from: 'top', amount: 0},
            align: 'center',
            delay: delay ? delay : 2000,
            width: 'auto',
            allow_dismiss: false
        });
    };

    $.tip.hideAll = function () {
        $('.bootstrap-growl').hide();
    };

    $.suc = function (message, delay) {
        return $.tip(message, 'success', delay);
    };

    $.err = function (message, delay) {
        return $.tip(message, 'danger', delay);
    };

    $.info = function (message, delay) {
        return $.tip(message, 'info', delay);
    };

    $.msg = function (result) {
        if (result.code > 0) {
            $.suc(result.message);
        } else {
            $.err(result.message);
        }
    };

    $.req = function(name) {
        name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }
})(jQuery);

function loadCss(url) {
    var link = document.createElement("link");
    link.type = "text/css";
    link.rel = "stylesheet";
    link.href = url;
    document.getElementsByTagName("head")[0].appendChild(link);
}

jQuery(function ($) {
    $('#sidebar a.dropdown-toggle').click(function () {
        var index = $(this).parent().index();
        if ($(this).next()[0].style.display !== 'none') {
            ace.data.set('menu-' + index, 0);
        } else {
            ace.data.set('menu-' + index, 1);
        }
    });

    // 左栏菜单高亮
    $('ul.nav-list a[href="' + window.location.pathname + '"]').parent().addClass('active');

    bootbox.setDefaults({
        locale: 'zh_CN'
    });

    $(document).ajaxError(function (event, request, settings) {
        // TODO 278
        var contentType = request.getResponseHeader('Content-Type');
        if (request.status === 200 && contentType.toLowerCase().indexOf('text/html') >= 0) {
            window.location.reload();
        } else {
            $.err('很抱歉,请求出错');
        }
    });

    // Ajax返回信息自动提示
    $.ajaxSetup({
        success: function(data) {
            if (typeof data =='object') {
                $.msg(data);
            }
        }
    });

    if ($.fn.bootstrapPaginator) {
        $.extend($.fn.bootstrapPaginator.defaults, {
            bootstrapMajorVersion: 3,
            numberOfPages: 5,
            pageUrl: function () {
                return 'javascript:;';
            },
            itemTexts: function (type, page, current) {
                switch (type) {
                    case 'first':
                        return '<i class="icon-double-angle-left"></i>';
                    case "prev":
                        return '<i class="icon-angle-left"></i>';
                    case "next":
                        return '<i class="icon-angle-right"></i>';
                    case "last":
                        return '<i class="icon-double-angle-right"></i>';
                    case "page":
                        return page;
                }
            }
        });
    }
});

function deleteRecord(link, dataTable) {
    var url = $(link).attr('href');
    bootbox.confirm('删除后将无法还原,确认删除?', function (result) {
        result && $.ajax({
            url: url,
            type: 'post',
            success: function (result) {
                dataTable.fnDraw(false);
                if (result.code > 0) {
                    $.suc(result.message);
                } else {
                    $.err(result.message);
                }
            }
        });
    });
    $('div.bootbox .modal-footer .btn-primary').addClass('btn-danger');
}

function nl2br(str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}