define(['jquery', 'datatables', 'datatables.bootstrap', 'template'], function(){

    // Reload dataTables with extra parameters
    $.extend(true, $.fn.dataTable.defaults, {
        dom: "t<'row'<'col-sm-6'ir><'col-sm-6'pl>>",
        processing: true,
        serverSide: true,
        autoWidth: false,
        dataSrc: 'data',
        columnDefs: [
            {
                targets: [ '_all' ],
                sortable: false
            }
        ],
        ajax: {
            dataSrc: 'data',
            data: function (data) {
                var params = {};
                $.each(data, function (key, val) {
                    params[val.name] = val.value;
                });
                var newParams = {
                    rows: params.iDisplayLength,
                    page: params.iDisplayStart / params.iDisplayLength + 1
                };
                data.length = 0;
                return newParams;
            },
            beforeSend: function (jqXHR, settings) {
                var origSuccess = settings.success;
                settings.success = function (json) {
                    if (undefined == json.iTotalRecords) {
                        json.iTotalRecords = json.records;
                    }
                    if (undefined == json.iTotalDisplayRecords) {
                        json.iTotalDisplayRecords = json.records;
                    }
                    origSuccess.apply(this, arguments)
                }
            }
        },
        language: {
            emptyTable: '搜索结果为空，请换其他试试吧。',
            sProcessing: "加载中...",
            sLengthMenu: "每页 _MENU_ 项结果",
            sZeroRecords: '查询结果为空，请换其他试试吧。',
            sInfo: "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
            sInfoEmpty: "显示第 0 至 0 项结果，共 0 项",
            sInfoFiltered: "(由 _MAX_ 项结果过滤)",
            sInfoPostFix: "",
            sInfoThousands: ' ',
            sSearch: "搜索:",
            sUrl: "",
            oPaginate: {
                sFirst: "首页",
                sPrevious: "上页",
                sNext: "下页",
                sLast: "末页"
            },
            oAria: {
                sSortAscending: ": activate to sort column ascending",
                sSortDescending: ": activate to sort column descending"
            }
        }
    });

    $.fn.dataTableExt.oApi.reload = function (setting, param, reset) {
        var origUrl = setting.ajax.url;
        setting.ajax.url = $.appendUrl(origUrl, param);
        this.fnFilter();
        if (reset == undefined || reset == true) {
            setting.ajax.url = origUrl;
        }
    };
});