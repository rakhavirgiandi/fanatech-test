<x-app-layout>
    <div class="container my-3">
        <form action="{{ route('inventory.update', ['id' => $inventory->id]) }}" method="post">
            @csrf
            @method('patch')
            <div class="mb-3">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $inventory->name)" />
                <div class="form-text text-danger" attr-error-message="name"></div>
            </div>
            <div class="mb-3">
                <x-input-label for="price" :value="__('Price')" />
                <x-text-input id="price" class="block mt-1 w-full" type="text" name="price" :value="old('price', $inventory->price)" />
                <div class="form-text text-danger" attr-error-message="price"></div>
            </div>
            <div class="mb-3">
                <x-input-label for="stock" :value="__('Stock')" />
                <x-text-input id="stock" class="block mt-1 w-full" type="text" name="stock" isNumber :value="old('stock', $inventory->stock)" />
                <div class="form-text text-danger" attr-error-message="stock"></div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    @once
        @push('scripts')
            <script>
                const baseURL = `{{ url('') }}`;
                $('form').submit(function(e){
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
                            console.log(response);
                            
                            Swal.fire({
                                icon: "success",
                                title: "Success!",
                                text: response.message,
                            }).then(({isConfirmed, isDismissed}) => {
                                if (isConfirmed === true || isDismissed == true) {
                                    window.location.href = baseURL+"/inventories";
                                }
                            });
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
