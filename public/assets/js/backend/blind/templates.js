define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'blind/templates/index' + location.search,
                    add_url: 'blind/templates/add',
                    edit_url: 'blind/templates/edit',
                    del_url: 'blind/templates/del',
                    multi_url: 'blind/templates/multi',
                    import_url: 'blind/templates/import',
                    levels_url: 'blind/levels/index',
                    table: 'blind_templates',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'game_type', title: __('Game_type'), operate: 'LIKE'},
                        {field: 'created_at', title: __('Created_at'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'updated_at', title: __('Updated_at'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: function (value, row, index) {
                            var that = $.extend({}, this);
                            var table = $(that.table).clone(true);

                            that.table = table;
                            var options = table.data('bootstrap.table').options;

                            if (options.extend.del_url !== '' && options.extend.del_url.split('/').pop() == 'del' && !row.deletetime) {
                                options.extend.del_url = options.extend.del_url.replace(/del$/, 'del') + '/ids/' + row[options.pk];
                            }

                            that.pk = options.pk;
                            var pdata = that.table.clone().attr('id', '');
                            pdata.find('.table').attr('id', '');
                            that.pdata = pdata;

                            var html = [];
                            var buttons = [];
                            var pk = options.pk;

                            // 级别管理按钮
                            if (options.extend.levels_url !== '') {
                                buttons.push({
                                    name: 'levels',
                                    icon: 'fa fa-list',
                                    title: __('级别管理'),
                                    extend: 'data-toggle="tooltip"',
                                    classname: 'btn btn-info btn-xs btn-levels-one',
                                    url: options.extend.levels_url + (options.extend.levels_url.indexOf('?') !== -1 ? '&' : '?') + 'template_id=' + row[pk]
                                });
                            }

                            // 编辑按钮
                            if (options.extend.edit_url !== '') {
                                buttons.push({
                                    name: 'edit',
                                    icon: 'fa fa-pencil',
                                    title: __('Edit'),
                                    extend: 'data-toggle="tooltip"',
                                    classname: 'btn btn-success btn-xs btn-editone',
                                    url: options.extend.edit_url.replace(/\{pk\}/g, row[pk])
                                });
                            }

                            // 删除按钮
                            if (options.extend.del_url !== '' && !row.deletetime) {
                                buttons.push({
                                    name: 'del',
                                    icon: 'fa fa-trash',
                                    title: __('Del'),
                                    extend: 'data-toggle="tooltip"',
                                    classname: 'btn btn-danger btn-xs btn-delone',
                                    url: options.extend.del_url.replace(/\{pk\}/g, row[pk])
                                });
                            }

                            // 生成HTML
                            $.each(buttons, function (i, j) {
                                var attr = j.url !== undefined ? 'href="' + j.url + '"' : '';
                                html.push('<a ' + attr + ' class="' + j.classname + '" title="' + j.title + '" ' + (j.extend || '') + '><i class="' + j.icon + '"></i></a>');
                            });

                            return html.join(' ');
                        }}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            // 绑定单个级别管理按钮事件
            $(document).on('click', '.btn-levels-one', function () {
                var url = $(this).attr('href');
                Fast.api.open(url, __('级别管理'), {
                    area: ['90%', '90%'],
                    callback: function (value) {
                        table.bootstrapTable('refresh');
                    }
                });
                return false;
            });
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
