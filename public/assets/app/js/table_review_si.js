var table_review_si = function () {
    return {

        //main function to initiate the module
        initDefault: function (url, header, order, sort) {
            var grid = new Datatable();
            
            grid.init({
                src: $("#table_review_si"),
                onSuccess: function (grid) {
                    //selesai load datatable
                    $($.fn.dataTable.tables(true)).css('width', '100%');
                    
                    //reload grafik
                    var arr = grid.getDataTable().ajax.json().data_grafik;
                    var data_grafik = [ { name: 'Not Yet Defined', y: arr[0] },
                                    { name: 'Red', y: arr[1] },
                                    { name: 'Yellow', y: arr[2] },
                                    { name: 'Green', y: arr[3] },
                                ];
                                
                    window.reload_grafik(data_grafik);

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

                        $('.table_review_si .form-filter').each(function(index, el) {
                            var name = $(el).attr('name');
                            var val = $(el).val();
                            if($(this).val() != ''){
                                if(val != null){
                                    oData[name] = val;
                                }
                            }
                        });

                        $('.table_review_si .global-filter').each(function(index, el) {
                            var name = $(el).attr('name');
                            var val = $(el).val();
                            if($(this).val() != ''){
                                if(val != null){
                                    oData[name] = val;
                                }
                            }
                        });

                        localStorage.setItem( 'DataTables_table_review_si_'+window.location.pathname, JSON.stringify(oData) );
                    },

                    //load parameter dari localstorage
                    "fnStateLoadParams": function(){
                        var filter = false;
                        var data = JSON.parse( localStorage.getItem('DataTables_table_review_si_'+window.location.pathname) );

                        $('.table_review_si .form-filter').each(function(){
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

                        $('.table_review_si .global-filter').each(function(){
                            var name = $(this).attr('name');
                            if(data[name] !== undefined){
                                if($(this).val() == ''){
                                     $(this).val(data[name]);
                                }
                            }
                        });

                        if(filter){
                            $('.table_review_si .filter').show();
                            // window.reload_table_review_si();
                        }
                    },
                    "order": order
                }
            });

            // Trigger filter jika klick enter pada input
            grid.getTableWrapper().on('keyup', '.form-filter', function (e) {
                if(e.keyCode == 13){
                    $('.table_review_si .filter-submit').first().click();
                }
            });

            // Trigger filter jika pilih select
            grid.getTableWrapper().on('change', 'select.form-filter, .select-filter', function (e) {
                $('.table_review_si .filter-submit').first().click();
            });

            //untuk reload table
            gridT = grid;
            grid_table_review_si = grid;
            
            window.reload_table_review_si = function (){
                grid_table_review_si.getDataTable().ajax.reload(null, false);
            }
        }
    };
}();