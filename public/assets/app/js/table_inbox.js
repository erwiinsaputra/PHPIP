var table_inbox = function () {
    return {

        //main function to initiate the module
        initDefault: function (url, header, order, sort) {
            var grid = new Datatable();

            grid.init({
                src: $("#table_inbox"),
                onSuccess: function (grid) {
                    //selesai load datatable
                    var tot_new = grid.getDataTable().ajax.json().tot_new;
                    var tot_review = grid.getDataTable().ajax.json().tot_review;
                    var tot_done = grid.getDataTable().ajax.json().tot_done;
                    var tot_all = grid.getDataTable().ajax.json().tot_all;
                    $('.tot_inbox_new').text(tot_new);
                    $('.tot_inbox_review').text(tot_review);
                    $('.tot_inbox_done').text(tot_done);
                    $('.tot_inbox_all').text(tot_all);
                },
                onError: function (grid) {
                    // execute some code on neertwork or other general error  
                },
                onDataLoad: function(grid) {
                    // execute some code on ajax data load
                    $('.tooltips').tooltip();
                },
                dataTable: {
                    "aoColumns": header,
                    "bStateSave": true,
                    "pageLength": 5,
                    "lengthMenu": [
                        [5, 10, 50, 100, 200, 300, 400, 500, -1],
                        [5, 10, 50, 100, 200, 300, 400, 500, "All"] 
                    ],
                    "ajax": {
                        "url": url,
                    },
                    "aoColumnDefs": [
                      { "bSortable": false, "aTargets": sort }
                    ],

                    //scroling untuk freeze
                    "scrollX":true,
                    "scrollY":"300px",
                    "scrollCollapse": true,
                    //untuk simpan parameter di localstorage
                    "fnStateSaveParams": function (oSettings, oData) {

                        $('.table_inbox .form-filter').each(function(index, el) {
                            var name = $(el).attr('name');
                            var val = $(el).val();
                            if($(this).val() != ''){
                                if(val != null){
                                    oData[name] = val;
                                }
                            }
                        });

                        $('.table_inbox .global-filter').each(function(index, el) {
                            var name = $(el).attr('name');
                            var val = $(el).val();
                            if($(this).val() != ''){
                                if(val != null){
                                    oData[name] = val;
                                }
                            }
                        });

                        localStorage.setItem( 'DataTables_table_inbox_'+window.location.pathname, JSON.stringify(oData) );
                    },

                    //load parameter dari localstorage
                    "fnStateLoadParams": function(){
                        var filter = false;
                        var data = JSON.parse( localStorage.getItem('DataTables_table_inbox_'+window.location.pathname) );

                        $('.table_inbox .form-filter').each(function(){
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

                        // $('.table_inbox .global-filter').each(function(){
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
                            $('.table_inbox .filter').show();
                            // window.reload_table_inbox();
                        }
                    },
                    "order": order
                }
            });

            // Trigger filter jika klick enter pada input
            grid.getTableWrapper().on('keyup', '.form-filter', function (e) {
                if(e.keyCode == 13){
                    $('.table_inbox .filter-submit').first().click();
                }
            });

            // Trigger filter jika pilih select
            grid.getTableWrapper().on('change', 'select.form-filter, .select-filter', function (e) {
                $('.table_inbox .filter-submit').first().click();
            });

            //untuk reload table
            gridT = grid;
            grid_table_inbox = grid;
            
            window.reload_table_inbox = function (){
                grid_table_inbox.getDataTable().ajax.reload(null, false);
            }
        }
    };
}();