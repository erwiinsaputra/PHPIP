var table_am_mydashboard = function () {
    return {

        //main function to initiate the module
        initDefault: function (url, header, order, sort) {
            var grid = new Datatable();

            grid.init({
                src: $("#table_am_mydashboard"),
                onSuccess: function (grid) {
                    //selesai load datatable
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
                        [5, 20, 50, 100, 200, 300, 400, 500, -1],
                        [5, 20, 50, 100, 200, 300, 400, 500, "All"] 
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

                        $('.table_am_mydashboard .form-filter').each(function(index, el) {
                            var name = $(el).attr('name');
                            var val = $(el).val();
                            if($(this).val() != ''){
                                if(val != null){
                                    oData[name] = val;
                                }
                            }
                        });

                        $('.table_am_mydashboard .global-filter').each(function(index, el) {
                            var name = $(el).attr('name');
                            var val = $(el).val();
                            if($(this).val() != ''){
                                if(val != null){
                                    oData[name] = val;
                                }
                            }
                        });

                        localStorage.setItem( 'DataTables_table_am_mydashboard_'+window.location.pathname, JSON.stringify(oData) );
                    },

                    //load parameter dari localstorage
                    "fnStateLoadParams": function(){
                        var filter = false;
                        var data = JSON.parse( localStorage.getItem('DataTables_table_am_mydashboard_'+window.location.pathname) );

                        $('.table_am_mydashboard .form-filter').each(function(){
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

                        // $('.table_am_mydashboard .global-filter').each(function(){
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
                            $('.table_am_mydashboard .filter').show();
                            // window.reload_table_am_mydashboard();
                        }
                    },
                    "order": order
                }
            });

            // Trigger filter jika klick enter pada input
            grid.getTableWrapper().on('keyup', '.form-filter', function (e) {
                if(e.keyCode == 13){
                    $('.table_am_mydashboard .filter-submit').first().click();
                }
            });

            // Trigger filter jika pilih select
            grid.getTableWrapper().on('change', 'select.form-filter, .select-filter', function (e) {
                $('.table_am_mydashboard .filter-submit').first().click();
            });

            //untuk reload table
            gridT = grid;
            grid_table_am_mydashboard = grid;
            
            window.reload_table_am_mydashboard = function (){
                grid_table_am_mydashboard.getDataTable().ajax.reload(null, false);
            }
        }
    };
}();