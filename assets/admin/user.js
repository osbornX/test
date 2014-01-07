define('wechat/user', function(require, exports, module){
    $('#export-csv').click(function(){
        var dt = $('#record-table').dataTable();
        window.location = $.appendUrl(dt.fnSettings().ajax.url, {page: 1, rows: 99999, format: 'csv'});
    });

    $('#check-all').click(function(){
        $('#record-table tbody input:checkbox').prop('checked', $(this).is(':checked'));
    });

    $('.table-responsive').on('click', 'input:checkbox', function(){
        $('#toGroupId').prop('disabled', !$('.table-responsive input:checkbox:checked').length);
    });

    // 批量分组
    $('#toGroupId').change(function(){
        var ids = $('#record-table input:checkbox:checked').map(function(){
            return $(this).val();
        }).get();

        $.post($.url('admin/user/moveGroup'), {groupId: $(this).val(), ids: ids}, function(result){
            $.msg(result);
            window.location.reload();
        });
    });

    var user = {};

    user.groupPanel = function () {
        var groupPrompt = function(value, id) {
            bootbox.prompt({
                title: '请输入用户组名称',
                value: value,
                callback: function (name){
                    if (name === null) {
                        return;
                    }
                    if (name == '') {
                        $.err('请输入用户组名称');
                        return false;
                    } else {
                        var ret;
                        $.ajax({
                            async: false,
                            url: id ? $.url('admin/group/update', {id: id}) : $.url('admin/group/create'),
                            data: {
                                name: name
                            },
                            success: function(result){
                                ret = result.code > 0;
                                $.msg(result);
                                if (ret) {
                                    window.location.reload();
                                }
                            }
                        });
                        return ret;
                    }
                }
            });
        };

        // 增加用户组
        $('.add-group').click(function(){
            groupPrompt();
        });

        // 编辑用户组
        $('.group-edit').click(function() {
            groupPrompt($(this).data('name'), $(this).data('id'));
        });

        // 删除用户组
        $('.group-destroy').click(function() {
            var id = $(this).data('id');
            bootbox.confirm({
                title: '确认删除?',
                message: '删除后,该分组里的用户将移动到未分组里,是否确定删除？',
                buttons: {
                    'cancel': {
                        label: '取消',
                        className: 'btn-default'
                    },
                    'confirm': {
                        label: '删除',
                        className: 'btn-danger'
                    }
                },
                callback: function(result) {
                    if (result) {
                        $.post($.url('admin/group/destroy', {id: id}), function(result){
                            $.msg(result);
                            window.location.reload();
                        });
                    }
                }
            });
        });
    };

    user.groupPanel();
});