function initDeleteConfirmation() {
    // Use jQuery selector
    $('.delete-confirm').each(function() {
        $(this).on('click', function(e) {
            e.preventDefault();
            const idSelector = $(this).data('id-selector')
            const form = $('#'+idSelector)
            const itemTitle = $(this).data('title') || 'this item';

            Swal.fire({
                title: 'Are you sure?',
                text: `Are you sure you want to delete ${itemTitle}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deleting...',
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
