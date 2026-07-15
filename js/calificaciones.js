new DataTable('#tcont', {
        responsive: true,
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados"
        },
        lengthMenu: [
            [10, 25, 50, -1],
            ['10 filas', '25 filas', '50 filas', 'Todos']
        ],
        layout: {
            topStart: {
                buttons: ['colvis', 'pageLength']
            },
            topEnd: ['search'],
            bottomStart: [{
                buttons: [{
                        extend: 'excel',
                        title: 'ProspectosExcel'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'ProspectosPDF'
                    },
                    {
                        extend: 'print'
                    }
                ]
            }, 'info'],
            bottomEnd: 'paging'
        }
    });


    $("#tcont_wrapper").removeClass("form-inline");
    $("#tcont_wrapper").addClass("w-100");