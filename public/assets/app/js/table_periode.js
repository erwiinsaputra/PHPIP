var table_periode = function () {
    return {

        //main function to initiate the module
        initDefault: function (url, header, order, sort) {
            var grid = new Datatable();

            grid.init({
                src: $("#table_periode"),
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

                        $('.table_periode .form-filter').each(function(index, el) {
                            var name = $(el).attr('name');
                            var val = $(el).val();
                            if($(this).val() != ''){
                                if(val != null){
                                    oData[name] = val;
                                }
                            }
                        });

                        $('.table_periode .global-filter').each(function(index, el) {
                            var name = $(el).attr('name');
                            var val = $(el).val();
                            if($(this).val() != ''){
                                if(val != null){
                                    oData[name] = val;
                                }
                            }
                        });

                        localStorage.setItem( 'DataTables_table_periode_'+window.location.pathname, JSON.stringify(oData) );
                    },

                    //load parameter dari localstorage
                    "fnStateLoadParams": function(){
                        var filter = false;
                        var data = JSON.parse( localStorage.getItem('DataTables_table_periode_'+window.location.pathname) );

                        $('.table_periode .form-filter').each(function(){
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

                        $('.table_periode .global-filter').each(function(){
                            var name = $(this).attr('name');
                            if(data[name] !== undefined){
                                if($(this).val() == ''){
                                     $(this).val(data[name]);
                                }
                            }
                        });

                        if(filter){
                            $('.table_periode .filter').show();
                            // window.reload_table_periode();
                        }
                    },
                    "order": order
                }
            });

            // Trigger filter jika klick enter pada input
            grid.getTableWrapper().on('keyup', '.form-filter', function (e) {
                if(e.keyCode == 13){
                    $('.table_periode .filter-submit').first().click();
                }
            });

            // Trigger filter jika pilih select
            grid.getTableWrapper().on('change', 'select.form-filter, .select-filter', function (e) {
                $('.table_periode .filter-submit').first().click();
            });

            //untuk reload table
            gridT = grid;
            grid_table_periode = grid;
            
            window.reload_table_periode = function (){
                grid_table_periode.getDataTable().ajax.reload(null, false);
            }
        }
    };
}();