<x-app-layout>
    
    <div class="container my-3">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('inventory.create') }}" class="btn btn-primary">Create</a>
        </div>
        <div class="table-responsive">
            {{ $dataTable->table() }}
        </div>
    </div>
    @once
    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    @endpush
    @endonce
</x-app-layout>
