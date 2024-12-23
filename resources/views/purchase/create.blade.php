<x-app-layout>
    <div class="container my-3">
        <form action="{{ route('purchase.store') }}" method="post">
            @csrf
            @method('post')
            <div class="mb-3">
                <x-input-label for="inventory" :value="__('Inventory')" />
                <select class="form-select" name="inventory_id" id="inventory">
                    <option value="">-- Select Inventory --</option>
                    @foreach ($inventories as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
                <div class="form-text text-danger" attr-error-message="inventory_id"></div>
            </div>
            <div class="mb-3">
                <x-input-label for="quantity" :value="__('Quantity')" />
                <x-text-input id="quantity" class="block mt-1 w-full" type="text" isNumber name="quantity" :value="old('quantity')" />
                <div class="form-text text-danger" attr-error-message="quantity"></div>
            </div>
            <div class="mb-3">
                <x-input-label for="date" :value="__('Date')" />
                <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date')" />
                <div class="form-text text-danger" attr-error-message="date"></div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    @once
        @push('scripts')
        <script>
                const baseURL = `{{ url('') }}`;
                $(document).ready(function() {
                    $('select[id="inventory"]').select2();
                    $('form').submit(function(e){
                        const button = $(this).find('button[type="submit"]');
                          e.preventDefault();
                          $.ajax({
                            url: $(this).attr('action'),
                            type: "POST",
                            data: new FormData(this),
                            contentType: false,
                            cache: false,
                            processData: false,
                            beforeSend: () => {
                                button.attr('disabled', true);
                                $('[attr-error-message]').html("");
                            },
                            success: function(response) {
                                console.log(response);
                                
                                Swal.fire({
                                    icon: "success",
                                    title: "Success!",
                                    text: response.message,
                                }).then(({isConfirmed, isDismissed}) => {
                                    if (isConfirmed === true || isDismissed == true) {
                                        window.location.href = baseURL+"/purchase";
                                    }
                                });
                            },
                            error: function(e) {
                                button.attr('disabled', false);
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
                                        text: e.responseText,
                                    });
                                }
                            }
                        });
                    });
                });
            </script>
        @endpush
    @endonce
</x-app-layout>
