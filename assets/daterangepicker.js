define(['moment', 'bootstrap-daterangepicker'], function(){
    var defaults = {
        startDate: moment().subtract('days', 29),
        endDate: moment(),
        minDate: '2013.01.01',
        maxDate: '2020.12.31',
        dateLimit: { days: 60 },
        showDropdowns: true,
        showWeekNumbers: true,
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,
        ranges: {
            '今天': [moment(), moment()],
            /*'昨天': [moment().subtract('days', 1), moment().subtract('days', 1)],*/
            '过去7天': [moment().subtract('days', 6), moment()],
            '过去30天': [moment().subtract('days', 29), moment()],
            '本月': [moment().startOf('month'), moment().endOf('month')]
            /*'上月': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]*/
        },
        opens: 'right',
        buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-primary',
        cancelClass: 'btn-small',
        format: 'YYYY.MM.DD',
        separator: '~',
        locale: {
            applyLabel: '搜索',
            cancelLabel: '取消',
            fromLabel: 'From',
            toLabel: 'To',
            customRangeLabel: '自定义范围',
            daysOfWeek: ['日', '一', '二', '三', '四', '五','六'],
            monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
            firstDay: 1
        }
    };

    var orig = $.fn.daterangepicker;
    $.fn.daterangepicker = function(options, cb) {
        options = $.extend(options, defaults);
        return orig.call(this, options, cb);
    }
});