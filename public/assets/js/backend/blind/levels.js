define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 获取URL中的template_id参数
            var urlParams = new URLSearchParams(location.search);
            var templateId = urlParams.get('template_id');
            var extraParams = templateId ? '?template_id=' + templateId : '';

            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'blind/levels/index' + location.search,
                    add_url: 'blind/levels/add' + extraParams,
                    edit_url: 'blind/levels/edit',
                    del_url: 'blind/levels/del',
                    multi_url: 'blind/levels/multi',
                    import_url: 'blind/levels/import',
                    table: 'blind_levels',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'level_number',
                sortOrder: 'asc', // 改为正序
                queryParams: function (params) {
                    // 确保template_id参数被传递
                    if (templateId) {
                        params.filter = JSON.stringify({template_id: templateId});
                        params.op = JSON.stringify({template_id: '='});
                    }
                    return params;
                },
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'template_id', title: __('Template_id'), operate: false, visible: false}, // 隐藏模板ID列，因为都是同一个模板
                        {field: 'level_number', title: __('Level_number'), operate: 'LIKE'},
                        {field: 'small_blind', title: __('Small_blind'), operate: 'LIKE'}, // 去掉 BB 后缀
                        {field: 'big_blind', title: __('Big_blind'), operate: 'LIKE'}, // 去掉 BB 后缀
                        {field: 'ante', title: __('Ante'), operate: 'LIKE', formatter: function(value) { return value > 0 ? value : __('No Ante'); }}, // 去掉 BB 后缀，只保留数值或"No Ante"
                        {field: 'duration', title: __('Duration'), operate: 'LIKE', formatter: function(value) { return value + ' 分钟'; }},
                        {field: 'break_after', title: __('Break_after'), formatter: Table.api.formatter.toggle},
                        {field: 'break_duration', title: __('Break_duration'), operate: 'LIKE', formatter: function(value) { return value > 0 ? value + ' 分钟' : '-'; }},
                        {field: 'created_at', title: __('Created_at'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updated_at', title: __('Updated_at'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
