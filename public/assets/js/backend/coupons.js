define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'coupons/index' + location.search,
                    add_url: 'coupons/add',
                    edit_url: 'coupons/edit',
                    del_url: 'coupons/del',
                    multi_url: 'coupons/multi',
                    import_url: 'coupons/import',
                    table: 'coupons',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                fixedColumns: true,
                fixedRightNumber: 1,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'coupon_type', title: __('Coupon_type'), searchList: {"tournament_ticket":__('Tournament_ticket'),"gift":__('Gift'),"event":__('Event')}, formatter: Table.api.formatter.normal},
                        {field: 'tournament_id', title: __('Tournament_id')},
                        {field: 'ticket_type', title: __('Ticket_type'), searchList: {"single":__('Single'),"multiple":__('Multiple'),"unlimited":__('Unlimited')}, formatter: Table.api.formatter.normal},
                        {field: 'usage_limit', title: __('Usage_limit')},
                        {field: 'total_limit', title: __('Total_limit')},
                        {field: 'used_count', title: __('Used_count')},
                        {field: 'valid_type', title: __('Valid_type'), searchList: {"fixed":__('Fixed'),"relative":__('Relative')}, formatter: Table.api.formatter.normal},
                        {field: 'valid_start_time', title: __('Valid_start_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'valid_end_time', title: __('Valid_end_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'valid_days', title: __('Valid_days')},
                        {field: 'status', title: __('Status'), searchList: {"active":__('Active'),"inactive":__('Inactive'),"expired":__('Expired')}, formatter: Table.api.formatter.status},
                        {field: 'sort_order', title: __('Sort_order')},
                        {field: 'image', title: __('Image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'created_at', title: __('Created_at'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'updated_at', title: __('Updated_at'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
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
