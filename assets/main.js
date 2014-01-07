require.config({
    baseUrl: '/assets/',
    urlArgs: 'v=2',// +  (new Date()).getTime(),
    paths: {
        'jquery': 'components/jquery-legacy/jquery.min',
        'timeago': 'components/jquery-timeago/jquery.timeago',
        'lazyload': 'components/jquery_lazyload/jquery.lazyload.min',
        'datatables': 'components/jquery.dataTables/jquery.dataTables', //components/DataTables/jquery.dataTables
        'datatables.bootstrap': 'js/jquery.dataTables.bootstrap',
        'template': 'components/artTemplate/template.min',
        'jquery-form': 'components/jquery-form/jquery.form',
        'loadJSON': 'components/jquery.loadJSON/index',
        'appendGrid': 'components/jquery.appendGrid/jquery.appendGrid-1.2.0',
        'jquery-bbq': 'components/jquery-bbq/jquery.ba-bbq',
        'ueditor': 'components/ueditor/lang/zh-cn/zh-cn',
        'ueditor.all': 'components/ueditor/ueditor.all.min',
        'ueditor.config': 'ueditor'
    },
    shim: {
        'datatables.bootstrap': {
            deps: ['datatables']
        },
        'ueditor': {
            deps: ['ueditor.all']
        },
        'ueditor.all': {
            deps: ['ueditor.config']
        }
    }
});