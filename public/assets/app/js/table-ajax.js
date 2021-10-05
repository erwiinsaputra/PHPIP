var TableAjax = function () {
    return {

        //main function to initiate the module
        initDefault: function (url, header, order, sort) {
            var grid = new Datatable();

            grid.init({
                src: $("#datatable_ajax"),
                onSuccess: function (grid) {
                    // execute some code after table records loaded
                },
                onError: function (grid) {
                    // execute some code on network or other general error  
                },
                onDataLoad: function(grid) {
                    // execute some code on ajax data load
                    $('.tooltips').tooltip();
                },
                dataTable: {
                    "aoColumns": header,
                    "bStateSave": true,
                    "pageLength": 10,
                    "lengthMenu": [
                        [10, 20, 50, 100, 150, -1],
                        [10, 20, 50, 100, 150, "All"] // change per page values here
                    ],
                    "ajax": {
                        "url": url
                    },
                    "aoColumnDefs": [
                      { "bSortable": false, "aTargets": sort }
                    ],
                    "fnStateSaveParams": function (oSettings, oData) {
                        var exist = localStorage.getItem('DataTables_datatable_ajax_'+window.location.pathname);
                        if(exist == null){
                            $('.datatable select[name="custom_status"]').selectpicker('val', '1');
                        }
                        $('.form-filter').each(function(index, el) {
                            var name = $(el).attr('name');
                            var val = $(el).val();

                            if($(this).val() != ''){
                                oData[name] = val;
                            }
                        });


                        var data_status = $('.datatable select[name="custom_status"]').val();
                        oData['data-status']    = data_status;

                        var aField = {};
                        checkbox.each(function () {
                            var field = $(this).attr('data-field');
                            aField[field] = $(this).is(':checked') ? true : false;
                        });

                        $.each(aField, function(index, value){
                            if(value) {
                                $('.datatable [class*="field-' + index + '"]').show();
                            }
                        });

                        oData['field']    = aField;

                        localStorage.setItem( 'DataTables_datatable_ajax_'+window.location.pathname, JSON.stringify(oData) );
                    },
                    "fnStateLoadParams": function(){
                        var filter = false;
                        var data = JSON.parse( localStorage.getItem('DataTables_datatable_ajax_'+window.location.pathname) );
                        $('.form-filter').each(function(){
                            var name = $(this).attr('name');
                            if(data[name] !== undefined){
                                $(this).val(data[name]);
                                filter = true;
                            }
                        });

                        var field = data['field'];
                        if(field !== undefined){
                            $.each(field, function(index, value){
                                if(value) {
                                    $('.datatable .field input[data-field="' + index + '"]').prop('checked', true);
                                }
                            })
                        }

                        if(filter){
                            $('#datatable_ajax .filter').show();
                        }

                        var data_status = data['data-status'];
                        $('.datatable select[name="custom_status"]').val(data_status);

                    },
                    "order": order
                }
            });

            // Trigger filter jika klick enter pada input
            grid.getTableWrapper().on('keyup', '.form-filter', function (e) {
                if(e.keyCode == 13){
                    $('.filter-submit').trigger('click');
                }
            });

            // Trigger filter jika pilih select
            grid.getTableWrapper().on('change', 'select.form-filter', function (e) {
                $('.filter-submit').trigger('click');
            });

            $('.datatable').on('change', 'select[name="custom_status"]', function () {
                var val = $(this).val();
                grid.setAjaxParam("data-status", val);
                grid.getDataTable().ajax.reload();
            });

            grid.getTableWrapper().on('change', '.select-filter', function (e) {
                $('.filter-submit').trigger('click');
            });

            // handle group actionsubmit button click
            grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
                e.preventDefault();
                var action = $(".table-group-action-input", grid.getTableWrapper());
                if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
                    
                    var message = 'Apakah anda yakin?';
                    var message_success = 'Data Berhasil Dirubah.';

                    if(action.attr('data-confirm') === 'false'){
                        grid.setAjaxParam("customActionType", "group_action");
                        grid.setAjaxParam("customActionName", action.val());
                        grid.setAjaxParam("id", grid.getSelectedRows());
                        grid.getDataTable().ajax.reload();
                        grid.clearAjaxParams();
                    }else{
                        swal({
                            title: "Are you sure?",
                            text: message,
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: 'Yes',
                            closeOnConfirm: false
                        },
                        function(){
                            grid.setAjaxParam("customActionType", "group_action");
                            grid.setAjaxParam("customActionName", action.val());
                            grid.setAjaxParam("id", grid.getSelectedRows());
                            grid.getDataTable().ajax.reload();
                            grid.clearAjaxParams();
                            swal("Success", message_success, "success");
                        });
                    }
                } else if (action.val() == "") {
                    Metronic.alert({
                        type: 'danger',
                        icon: 'warning',
                        message: 'Please select an action',
                        container: grid.getTableWrapper(),
                        place: 'prepend'
                    });
                } else if (grid.getSelectedRowsCount() === 0) {
                    Metronic.alert({
                        type: 'danger',
                        icon: 'warning',
                        message: 'No record selected',
                        container: grid.getTableWrapper(),
                        place: 'prepend'
                    });
                }
            });

            var checkbox = $('.datatable .field input');
            checkbox.click(function(){
                var ele = $(this);
                var field = $(this).attr('data-field');
                if(ele.is(':checked')){
                    $('.field-'+field).fadeIn(400);
                }else{
                    $('.field-'+field).fadeOut(400);
                }

                var data = JSON.parse( localStorage.getItem('DataTables_datatable_ajax_'+window.location.pathname) );

                var aField = {};
                checkbox.each(function () {
                    var field = $(this).attr('data-field');
                    aField[field] = $(this).is(':checked') ? true : false;
                });

                data['field'] = aField;
                localStorage.setItem( 'DataTables_datatable_ajax_'+window.location.pathname, JSON.stringify(data) );
            });

            gridT = grid;
        }

    };

}();






var TableAjax2 = function () {
    return {

        //main function to initiate the module
        initDefault: function (url, header, order, sort) {
            var grid = new Datatable();

            grid.init({
                src: $("#datatable_ajax"),
                onSuccess: function (grid) {
                    // execute some code after table records loaded
                    $('#datatable_ajax_wrapper').children('row').hide();
                },
                onError: function (grid) {
                    // execute some code on network or other general error  
                },
                onDataLoad: function(grid) {
                    // execute some code on ajax data load
                    $('.tooltips').tooltip();
                },
                dataTable: {
                    "aoColumns": header,
                    "bStateSave": true,
                    "pageLength": "All",
                    "lengthMenu": [
                        [ -1],
                        ["All"] // change per page values here
                    ],
                    "ajax": {
                        "url": url
                    },
                    "aoColumnDefs": [
                      { "bSortable": false, "aTargets": sort }
                    ],
                    "fnStateSaveParams": function (oSettings, oData) {
                        var exist = localStorage.getItem('DataTables_datatable_ajax_'+window.location.pathname);
                        if(exist == null){
                            $('.datatable select[name="custom_status"]').selectpicker('val', '1');
                        }
                        $('.form-filter').each(function(index, el) {
                            var name = $(el).attr('name');
                            var val = $(el).val();

                            if($(this).val() != ''){
                                oData[name] = val;
                            }
                        });


                        var data_status = $('.datatable select[name="custom_status"]').val();
                        oData['data-status']    = data_status;

                        var aField = {};
                        checkbox.each(function () {
                            var field = $(this).attr('data-field');
                            aField[field] = $(this).is(':checked') ? true : false;
                        });

                        $.each(aField, function(index, value){
                            if(value) {
                                $('.datatable [class*="field-' + index + '"]').show();
                            }
                        });

                        oData['field']    = aField;

                        localStorage.setItem( 'DataTables_datatable_ajax_'+window.location.pathname, JSON.stringify(oData) );
                    },
                    "fnStateLoadParams": function(){
                        var filter = false;
                        var data = JSON.parse( localStorage.getItem('DataTables_datatable_ajax_'+window.location.pathname) );
                        $('.form-filter').each(function(){
                            var name = $(this).attr('name');
                            if(data[name] !== undefined){
                                $(this).val(data[name]);
                                filter = true;
                            }
                        });

                        var field = data['field'];
                        if(field !== undefined){
                            $.each(field, function(index, value){
                                if(value) {
                                    $('.datatable .field input[data-field="' + index + '"]').prop('checked', true);
                                }
                            })
                        }

                        if(filter){
                            $('#datatable_ajax .filter').show();
                        }

                        var data_status = data['data-status'];
                        $('.datatable select[name="custom_status"]').val(data_status);

                    },
                    "order": order
                }
            });

            // Trigger filter jika klick enter pada input
            grid.getTableWrapper().on('keyup', '.form-filter', function (e) {
                if(e.keyCode == 13){
                    $('.filter-submit').trigger('click');
                }
            });

            // Trigger filter jika pilih select
            grid.getTableWrapper().on('change', 'select.form-filter', function (e) {
                $('.filter-submit').trigger('click');
            });

            $('.datatable').on('change', 'select[name="custom_status"]', function () {
                var val = $(this).val();
                grid.setAjaxParam("data-status", val);
                grid.getDataTable().ajax.reload();
            });

            grid.getTableWrapper().on('change', '.select-filter', function (e) {
                $('.filter-submit').trigger('click');
            });

            // handle group actionsubmit button click
            grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
                e.preventDefault();
                var action = $(".table-group-action-input", grid.getTableWrapper());
                if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
                    var message = 'Default Message';
                    var message_success = 'Default Success Message';

                    if(action.val() == '0'){
                        message = 'Apakah anda yakin akan Menonaktifkan Data ?';
                        message_success = 'Data anda Berhasil dinonaktifkan.';
                    }else if(action.val() == '1'){
                        message = 'Apakah anda yakin akan Mengaktifkan Data ?';
                        message_success = 'Data anda berhasil diaktifkan.';
                    }else if(action.val() == '99'){
                        message = 'Apakah anda yakin akan Menghapus Data ?';
                        message_success = 'Data anda berhasil dihapus.';
                    }else if(action.val() == '98'){
                        message = 'Apakah anda yakin akan Menghapus permanen Data ?';
                        message_success = 'Data anda berhasil dihapus permanen.';
                    }

                    if(action.attr('data-confirm') === 'false'){
                        grid.setAjaxParam("customActionType", "group_action");
                        grid.setAjaxParam("customActionName", action.val());
                        grid.setAjaxParam("id", grid.getSelectedRows());
                        grid.getDataTable().ajax.reload();
                        grid.clearAjaxParams();
                    }else{
                        swal({
                            title: "Are you sure?",
                            text: message,
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: 'Yes',
                            closeOnConfirm: false
                        },
                        function(){
                            grid.setAjaxParam("customActionType", "group_action");
                            grid.setAjaxParam("customActionName", action.val());
                            grid.setAjaxParam("id", grid.getSelectedRows());
                            grid.getDataTable().ajax.reload();
                            grid.clearAjaxParams();
                            swal("Success", message_success, "success");
                        });
                    }
                } else if (action.val() == "") {
                    Metronic.alert({
                        type: 'danger',
                        icon: 'warning',
                        message: 'Please select an action',
                        container: grid.getTableWrapper(),
                        place: 'prepend'
                    });
                } else if (grid.getSelectedRowsCount() === 0) {
                    Metronic.alert({
                        type: 'danger',
                        icon: 'warning',
                        message: 'No record selected',
                        container: grid.getTableWrapper(),
                        place: 'prepend'
                    });
                }
            });

            var checkbox = $('.datatable .field input');
            checkbox.click(function(){
                var ele = $(this);
                var field = $(this).attr('data-field');
                if(ele.is(':checked')){
                    $('.field-'+field).fadeIn(400);
                }else{
                    $('.field-'+field).fadeOut(400);
                }

                var data = JSON.parse( localStorage.getItem('DataTables_datatable_ajax_'+window.location.pathname) );

                var aField = {};
                checkbox.each(function () {
                    var field = $(this).attr('data-field');
                    aField[field] = $(this).is(':checked') ? true : false;
                });

                data['field'] = aField;
                localStorage.setItem( 'DataTables_datatable_ajax_'+window.location.pathname, JSON.stringify(data) );
            });

            gridT = grid;
        }

    };

}();