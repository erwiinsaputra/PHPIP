var table_monev_si = function () {
    return {

        //main function to initiate the module
        initDefault: function (url, header, order, sort) {
            var grid = new Datatable();

            grid.init({
                src: $("#table_monev_si"),
                onSuccess: function (grid) {
                    //selesai load datatable
                    $($.fn.dataTable.tables(true)).css('width', '100%');
                },
                onError: function (grid) {
                    // execute some code on neertwork or other general error  
                },
                onDataLoad: function(grid) {
                    // execute some code on ajax data load
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

                        $('.table_monev_si .form-filter').each(function(index, el) {
                            var name = $(el).attr('name');
                            var val = $(el).val();
                            if($(this).val() != ''){
                                if(val != null){
                                    oData[name] = val;
                                }
                            }
                        });

                        $('.table_monev_si .global-filter').each(function(index, el) {
                            var name = $(el).attr('name');
                            var val = $(el).val();
                            if($(this).val() != ''){
                                if(val != null){
                                    oData[name] = val;
                                }
                            }
                        });

                        localStorage.setItem( 'DataTables_table_monev_si_'+window.location.pathname, JSON.stringify(oData) );
                    },

                    //load parameter dari localstorage
                    "fnStateLoadParams": function(){
                        var filter = false;
                        var data = JSON.parse( localStorage.getItem('DataTables_table_monev_si_'+window.location.pathname) );

                        $('.table_monev_si .form-filter').each(function(){
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

                        // $('.table_monev_si .global-filter').each(function(){
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
                            $('.table_monev_si .filter').show();
                            // window.reload_table_monev_si();
                        }
                    },
                    "order": order
                }
            });

            // Trigger filter jika klick enter pada input
            grid.getTableWrapper().on('keyup', '.form-filter', function (e) {
                if(e.keyCode == 13){
                    $('.table_monev_si .filter-submit').first().click();
                }
            });

            // Trigger filter jika pilih select
            grid.getTableWrapper().on('change', 'select.form-filter, .select-filter', function (e) {
                $('.table_monev_si .filter-submit').first().click();
            });

            //untuk reload table
            gridT = grid;
            grid_table_monev_si = grid;
            
            window.reload_table_monev_si = function (){
                grid_table_monev_si.getDataTable().ajax.reload(null, false);
            }
        }
    };
}();