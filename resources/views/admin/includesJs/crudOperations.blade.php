<script>

    // To create model
    function createModel(button) {
        // Prevent the default form submission behavior
        event.preventDefault();

        // Unbind any previous submit event handlers to prevent multiple bindings
        var formSelector = button ? $(button).closest('form') : $('#createForm');
        var url = formSelector.attr('action');
        var tableSelector = '#dataTable';

        var formData = new FormData(formSelector[0]); // Use FormData to handle files

        // Ajax call to submit the form data
        $.ajax({
            url: url,
            method: "POST",
            data: formData, // Use FormData for correct file handling
            contentType: false, // No need to set contentType when using FormData
            processData: false, // No need to process data when using FormData
            success: function (response) {
                // Hide the modal
                formSelector.closest('.modal').hide();
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();

                // Clear previous errors
                formSelector.find('.alert-danger').remove();

                // Reset the form
                formSelector.trigger('reset');

                // Reload DataTables
                if (tableSelector && $(tableSelector).length) {
                    $(tableSelector).DataTable().ajax.reload();
                }

                // Show success message
                Swal.fire({
                    icon: 'success',
                    text: '{{ trns('added_successfully') }}',
                    timer: 1500, // Time in milliseconds (2 seconds)
                    timerProgressBar: true, // Show a progress bar
                    showConfirmButton: false // Show "OK" button
                });
            },
            error: function (xhr) {
                console.log('Error response:', xhr); // Log the entire response

                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors || {}; // Accessing the errors property
                    console.log('Validation errors:', errors); // Log the errors

                    // Clear previous errors
                    formSelector.find('.alert-danger').remove();

                    // Show new errors
                    $.each(errors, function (key, messages) {
                        // messages is an array of strings
                        if (Array.isArray(messages)) {
                            messages.forEach(function (message) {
                                formSelector.prepend('<div class="alert alert-danger">' + message + '</div>');
                            });
                        }
                    });
                } else {
                    alert('An error occurred. Please try again.');
                }
            }
        });
    }


    // To update model
    function updateModel(rowId) {
        var formSelector = $('#editModelForm_' + rowId);
        var url = formSelector.attr('action');
        var method = formSelector.attr('method');
        var tableSelector = '#dataTable';

        // Use FormData to handle files
        var formData = new FormData(formSelector[0]);

        $.ajax({
            type: method,
            url: url,
            data: formData, // Use FormData for correct file handling
            contentType: false, // No need to set contentType when using FormData
            processData: false, // No need to process data when using FormData
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                // Hide the modal
                // $(formSelector).closest('.modal').modal('hide');
                formSelector.closest('.modal').hide();
                $('#modalsContainer').html(''); // Clear the modals container
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();

                // Clear previous errors
                $(formSelector).find('.alert-danger').remove();

                // Reset the form
                formSelector.trigger('reset');

                // Reload DataTables
                if (tableSelector && $(tableSelector).length) {
                    $(tableSelector).DataTable().ajax.reload();
                }

                // Show success message
                Swal.fire({
                    icon: 'success',
                    text: '{{ trns('updated_successfully') }}',
                    timer: 1500, // Time in milliseconds (2 seconds)
                    timerProgressBar: true, // Show a progress bar
                    showConfirmButton: false // Show "OK" button
                });
            },
            error: function (xhr) {
                console.log('Error response:', xhr); // Log the entire response

                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors || {}; // Accessing the errors property
                    console.log('Validation errors:', errors); // Log the errors

                    // Clear previous errors
                    $(formSelector).find('.alert-danger').remove();

                    // Show new errors
                    // $.each(xhr.responseJSON, function (index, value) {
                    //     console.log('Error message:', value); // Log each error message
                    //     $(formSelector).prepend('<div class="alert alert-danger">' + value + '</div>');
                    // });

                    // Show new errors
                    $.each(errors, function (key, messages) {
                        // messages is an array of strings
                        if (Array.isArray(messages)) {
                            messages.forEach(function (message) {
                                formSelector.prepend('<div class="alert alert-danger">' + message + '</div>');
                            });
                        }
                    });
                } else {
                    alert('An error occurred. Please try again.');
                }
            }
        });
    }


    // To delete model
    function deleteModel(rowId) {
        var formSelector = $('#deleteModelForm_' + rowId);
        var url = formSelector.attr('action');
        var tableSelector = '#dataTable';
        $.ajax({
            url: url,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                // Hide the modal
                formSelector.closest('.modal').hide();
                $('.modal-backdrop').remove();
                // Delete row
                var table = $(tableSelector).DataTable();
                table
                    .row($('[data-id="' + rowId + '"]'))
                    .remove()
                    .draw();

                // Reload DataTables
                if (tableSelector && $(tableSelector).length) {
                    console.log('Reloading DataTables...');
                    $(tableSelector).DataTable().ajax.reload();
                }

                Swal.fire({
                    icon: 'success',
                    text: '{{ trns('deleted_successfully') }}',
                    timer: 1500, // Time in milliseconds (1 second)
                    timerProgressBar: true, // Show a progress bar
                    showConfirmButton: false // Hide "OK" button
                });
                $('#groups-table').DataTable().ajax.reload(); // Reload DataTables
            },
            error: function (xhr) {
                console.log(xhr);
                Swal.fire({
                    icon: 'error',
                    text: 'Failed to delete element.'
                });
            }
        });
    }
</script>

@include('admin/includesJs/crudModals')
