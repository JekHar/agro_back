function initDeleteConfirmation() {
    // Use jQuery selector
    $('.delete-confirm').each(function() {
        $(this).on('click', function(e) {
            e.preventDefault();
            const idSelector = $(this).data('id-selector')
            const form = $('#'+idSelector)
            const itemTitle = $(this).data('title') || 'this item';

            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Estás seguro de eliminar ${itemTitle}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Eliminando...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    form.submit();
                }
            });
        });
    });
}
