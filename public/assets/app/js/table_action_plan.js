var table_action_plan = function () {
    return {

        //main function to initiate the module
        initDefault: function (url, header, order, sort) {
            var grid = new Datatable();

            grid.init({
                src: $("#table_action_plan"),
                onSuccess: function (grid) {

                    //rapihkan table css
                    $($.fn.dataTable.tables(true)).css('width', '100%');

                    //cek data ic jika kosong tampilkan pesan message_empty_data_ic
                    var total_data = parseFloat(grid.getDataTable().ajax.json().total_data);
                    if(total_data > 0){
                        $('#message_empty_data_ic').hide();
                    }else{
                        $('#message_empty_data_ic').show();
                    }

                    //cek edit data untuk pic ic manager
                    var editing = grid.getDataTable().ajax.json().editing;
                    if(editing == 'yes'){
                        $('.table_action_plan .btn_add').show();
                    }else{
                        $('.table_action_plan .btn_add').hide();
                    }

                },
                onError: function (grid) {
                    // execute some code on neertwork or other general error  
                },
                onDataLoad: function(grid) {
                    
                    //rapihkan tooltip
                    $('.tooltips').tooltip();

                    $($.fn.dataTable.tables(true)).css('width', '100%');

                },
                dataTable: {
                    "aoColumns": header,
                    "bStateSave": true,
                    "pageLength": 10,
                    "lengthMenu": [
                        [10, 20, 50, 100, 200, 300, 400, 500, -1],
                        [10, 20, 50, 100, 200, 300, 400, 500, "All"] 
                    ],
                    "ajax": {
                        "url": url,
                    },
                    "aoColumnDefs": [
                      { "bSortable": false, "aTargets": sort }
                    ],

                    //scroling untuk freeze
                    "scrollX":true,
                    "scrollY":"500px",
                    "scrollCollapse": true,

                    //untuk simpan parameter di localstorage
                    "fnStateSaveParams": function (oSettings, oData) {

                        $('.table_action_plan .form-filter').each(function(index, el) {
                            var name = $(el).attr('name');
                            var val = $(el).val();
                            if($(this).val() != ''){
                                if(val != null){
                                    oData[name] = val;
                                }
                            }
                        });

                        $('.table_action_plan .global-filter').each(function(index, el) {
                            var name = $(el).attr('name');
                            var val = $(el).val();
                            if($(this).val() != ''){
                                if(val != null){
                                    oData[name] = val;
                                }
                            }
                        });

                        localStorage.setItem( 'DataTables_table_action_plan_'+window.location.pathname, JSON.stringify(oData) );
                    },

                    //load parameter dari localstorage
                    "fnStateLoadParams": function(){
                        var filter = false;
                        var data = JSON.parse( localStorage.getItem('DataTables_table_action_plan_'+window.location.pathname) );

                        $('.table_action_plan .form-filter').each(function(){
                            var name = $(this).attr('name');
                            if(data[name] !== undefined){
                                if(data[name] != null){

                                    if($(this).val() == '' || $(this).val() == null){

                                        $(this).val(data[name]);

                                        if($(this.getDetails).hasClass("select-filter")){

                                            $(this).trigger('change');
                                        }
                                    }

                                    filter = true;
                                }
                            }
                        });

                        // $('.table_action_plan .global-filter').each(function(){
                        //     var name = $(this).attr('name');
                        //     if(data[name] !== undefined){
                        //         if(data[name] != null){

                        //             if($(this).val() == '' || $(this).val() == null){

                        //                 $(this).val(data[name]);

                        //                 if($(this.getDetails).hasClass("select-filter")){

                        //                     $(this).trigger('change');
                        //                 }
                        //             }

                        //             filter = true;
                        //         }
                        //     }
                        // });

                        if(filter){
                            $('.table_action_plan .filter').show();
                            // window.reload_table_action_plan();
                        }
                    },
                    "order": order
                }
            });

            // Trigger filter jika klick enter pada input
            grid.getTableWrapper().on('keyup', '.form-filter', function (e) {
                if(e.keyCode == 13){
                    $('.table_action_plan .filter-submit').first().click();
                }
            });

            // Trigger filter jika pilih select
            grid.getTableWrapper().on('change', 'select.form-filter, .select-filter', function (e) {
                $('.table_action_plan .filter-submit').first().click();
            });

            //untuk reload table
            gridT = grid;
            grid_table_action_plan = grid;
            
            window.reload_table_action_plan = function (){
                grid_table_action_plan.getDataTable().ajax.reload(null, false);
            }
        }
    };
}();