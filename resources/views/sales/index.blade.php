<x-app-layout>
    
    <div class="container my-3">
        <div class="d-flex justify-content-end mb-3">
            @if (Auth::user()->hasRole(['super-admin', 'sales']))
                <a href="{{ route('sales.create') }}" class="btn btn-primary">Create</a>
            @endif
        </div>
        <div class="table-responsive">
            {{ $dataTable->table() }}
        </div>
    </div>
    @once
    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            $(document).on('submit', '[attr-datatable-submit="delete"]', function(e){
                  e.preventDefault();
                  $.ajax({
                    url: $(this).attr('action'),
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: () => {
                        $('[attr-error-message]').html("");
                    },
                    success: function(response) {
                        $('#sales-table').DataTable().ajax.reload();
                        Swal.fire({
                            icon: "success",
                            title: "Success!",
                            text: response.message,
                        })
                    },
                    error: function(e) {
                        const response = e.responseJSON;
                        if (response?.errors && e.status === 422) {
                            const errors = response?.errors;
                            $.each(errors, function(index, val) {
                                $('[attr-error-message="'+index+'"]').html(val[0]);
                            })
                        } else if (e.status === 500) {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Something went wrong!"
                            })
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: e.responseJSON.errors ? e.responseJSON.errors : e.responseText,
                            });
                        }
                    }
                });
            });
        </script>
    @endpush
    @endonce
</x-app-layout>
