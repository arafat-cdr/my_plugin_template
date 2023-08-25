jQuery(document).ready(function($) {


    // select 2
    $(".multiple_select_tags").select2({
      tags: true,
      tokenSeparators: [','],
    });

    $(".multiple_select_areas").select2({
      tags: true
    });

    $(".single_select_2").select2();

    // datatable
     $('.wl_table').DataTable();
     $('.dataTables_length select').css({'min-width': '100px !important;'})

      // $('.wl_table_length').css({'margin-left': '10%'});
      // $('.wl_table_info').css({'margin-left': '10%'});

      // $('.wl_table_filter').css({'margin-right': '10%'});
      // $('.wl_table_paginate').css({'margin-right': '10%'});
     // end data table


    $('#filter_city').on('change', function() {
            const selectedCity = $(this).val();
            const currentUrl = new URL(window.location.href);
            
            if (selectedCity) {
                currentUrl.searchParams.set('filter_city', selectedCity);
            } else {
                currentUrl.searchParams.delete('filter_city');
            }
            
            window.location.href = currentUrl.toString();
        });



});
