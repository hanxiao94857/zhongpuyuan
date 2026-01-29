define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    // 自动初始化表单方法
    $(document).ready(function() {
        if ($('[data-jsname="tournaments/prizes-add"]').length > 0) {
            Controller.prizesadd();
        }
        if ($('[data-jsname="tournaments/prizes-edit"]').length > 0) {
            Controller.prizesedit();
        }
    });

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'tournaments/index' + location.search,
                    add_url: 'tournaments/add',
                    edit_url: 'tournaments/edit',
                    detail_url: 'tournaments/detail',
                    prizes_url: 'tournaments/prizes',
                    del_url: 'tournaments/del',
                    multi_url: 'tournaments/multi',
                    import_url: 'tournaments/import',
                    table: 'tournaments',
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
                        {field: 'tournament_type', title: __('Tournament_type'), searchList: {"mtt":__('Mtt'),"sng":__('Sng'),"satellite":__('Satellite')}, formatter: Table.api.formatter.normal},
                        {field: 'blind_template_id', title: __('Blind_template_id')},
                        {field: 'buy_in_points', title: __('Buy_in_points')},
                        {field: 'buy_in_coupon_id', title: __('Buy_in_coupon_id')},
                        {field: 'buy_in_type', title: __('Buy_in_type'), searchList: {"points_only":__('Points_only'),"coupon_only":__('Coupon_only'),"points_and_coupon":__('Points_and_coupon'),"free":__('Free')}, formatter: Table.api.formatter.normal},
                        {field: 'initial_chips', title: __('Initial_chips')},
                        {field: 'max_players', title: __('Max_players')},
                        {field: 'min_players', title: __('Min_players')},
                        {field: 'start_time', title: __('Start_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'late_registration_time', title: __('Late_registration_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'estimated_duration', title: __('Estimated_duration')},
                        {field: 'tournament_status', title: __('Tournament_status'), searchList: {"upcoming":__('Upcoming'),"running":__('Running'),"paused":__('Paused'),"completed":__('Completed'),"cancelled":__('Cancelled')}, formatter: Table.api.formatter.status},
                        {field: 'registration_status', title: __('Registration_status'), searchList: {"closed":__('Closed'),"open":__('Open'),"late_open":__('Late_open')}, formatter: Table.api.formatter.status},
                        {field: 'current_level', title: __('Current_level')},
                        {field: 'current_players', title: __('Current_players')},
                        {field: 'current_tables', title: __('Current_tables')},
                        {field: 'auto_balance', title: __('Auto_balance')},
                        {field: 'created_at', title: __('Created_at'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'updated_at', title: __('Updated_at'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: function (value, row, index) {
                            var table = $(this.table);
                            var options = table.data('bootstrap.table').options;
                            var buttons = [];

                            // 详情按钮
                            buttons.push({
                                name: 'detail',
                                icon: 'fa fa-eye',
                                title: __('Detail'),
                                extend: 'data-toggle="tooltip"',
                                classname: 'btn btn-info btn-xs btn-detailone',
                                url: options.extend.detail_url + '/ids/' + row[options.pk]
                            });

                            // 奖励配置按钮
                            buttons.push({
                                name: 'prizes',
                                icon: 'fa fa-trophy',
                                title: __('奖励配置'),
                                extend: 'data-toggle="tooltip"',
                                classname: 'btn btn-warning btn-xs btn-prizesone',
                                url: options.extend.prizes_url + '?tournament_id=' + row[options.pk]
                            });

                            // 编辑按钮
                            buttons.push({
                                name: 'edit',
                                icon: 'fa fa-pencil',
                                title: __('Edit'),
                                extend: 'data-toggle="tooltip"',
                                classname: 'btn btn-success btn-xs btn-editone',
                                url: options.extend.edit_url + '/ids/' + row[options.pk]
                            });

                            // 删除按钮
                            if (!row.deletetime) {
                                buttons.push({
                                    name: 'del',
                                    icon: 'fa fa-trash',
                                    title: __('Del'),
                                    extend: 'data-toggle="tooltip"',
                                    classname: 'btn btn-danger btn-xs btn-delone',
                                    url: options.extend.del_url + '/ids/' + row[options.pk]
                                });
                            }

                            var html = [];
                            $.each(buttons, function (i, j) {
                                var attr = (j.name === 'detail' || j.name === 'prizes') ? 'data-url="' + j.url + '"' : 'href="' + j.url + '"';
                                html.push('<a ' + attr + ' class="' + j.classname + '" title="' + j.title + '" ' + (j.extend || '') + '><i class="' + j.icon + '"></i></a>');
                            });

                            return html.join(' ');
                        }}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            // 绑定详情按钮事件
            $(document).on('click', '.btn-detailone', function () {
                var url = $(this).attr('href') || $(this).data('url');
                if (url) {
                    Backend.api.addtabs(url, __('Detail'));
                }
                return false;
            });

            // 绑定奖励配置按钮事件
            $(document).on('click', '.btn-prizesone', function () {
                var url = $(this).attr('href') || $(this).data('url');
                if (url) {
                    Fast.api.open(url, __('奖励配置'), {
                        area: ['90%', '90%'],
                        callback: function (value) {
                            table.bootstrapTable('refresh');
                        }
                    });
                }
                return false;
            });
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        prizes: function () {
            // 获取比赛ID - 从URL参数获取
            var urlParams = new URLSearchParams(location.search);
            var tournamentId = urlParams.get('tournament_id') || 0;

            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'tournaments/prizes?tournament_id=' + tournamentId,
                    add_url: 'tournaments/prizesadd?tournament_id=' + tournamentId,
                    edit_url: 'tournaments/prizesedit?tournament_id=' + tournamentId,
                    del_url: 'tournaments/prizesdel',
                    table: 'tournament_prizes',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'position',
                sortOrder: 'asc',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'position_text', title: __('Position')},
                        {field: 'reward_type_text', title: __('Reward_type')},
                        {field: 'points_reward_text', title: __('Points_reward')},
                        {field: 'coupon_text', title: __('Coupon')},
                        {field: 'min_players_text', title: __('Min_players')},
                        {field: 'description', title: __('Description')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: function (value, row, index) {
                            var that = $.extend({}, this);
                            var table = $(that.table).clone(true);

                            that.table = table;
                            var options = table.data('bootstrap.table').options;

                            if (options.extend.edit_url !== '' && !row.deletetime) {
                                options.extend.edit_url = options.extend.edit_url + '&ids=' + row[options.pk];
                            }

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
        },
        prizesadd: function () {
            // 奖励类型变更事件
            $('select[name="row[reward_type]"]').change(function() {
                var rewardType = $(this).val();

                if (rewardType == 'points_only' || rewardType == 'points_and_coupon') {
                    $('.points-group').show();
                    $('input[name="row[points_reward]"]').attr('required', 'required');
                } else {
                    $('.points-group').hide();
                    $('input[name="row[points_reward]"]').removeAttr('required').val('0');
                }

                if (rewardType == 'coupon_only' || rewardType == 'points_and_coupon') {
                    $('.coupon-group').show();
                    $('select[name="row[coupon_id]"]').attr('required', 'required');
                } else {
                    $('.coupon-group').hide();
                    $('select[name="row[coupon_id]"]').removeAttr('required').val('');
                }
            });

            // 初始化
            $('select[name="row[reward_type]"]').trigger('change');

            // 绑定表单事件
            Controller.api.bindevent();
        },
        prizesedit: function () {
            // 奖励类型变更事件
            $('select[name="row[reward_type]"]').change(function() {
                var rewardType = $(this).val();

                if (rewardType == 'points_only' || rewardType == 'points_and_coupon') {
                    $('.points-group').show();
                    $('input[name="row[points_reward]"]').attr('required', 'required');
                } else {
                    $('.points-group').hide();
                    $('input[name="row[points_reward]"]').removeAttr('required').val('0');
                }

                if (rewardType == 'coupon_only' || rewardType == 'points_and_coupon') {
                    $('.coupon-group').show();
                    $('select[name="row[coupon_id]"]').attr('required', 'required');
                } else {
                    $('.coupon-group').hide();
                    $('select[name="row[coupon_id]"]').removeAttr('required').val('');
                }
            });

            // 初始化
            $('select[name="row[reward_type]"]').trigger('change');

            // 绑定表单事件
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
