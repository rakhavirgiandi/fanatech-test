<x-app-layout>
    
    <div class="container mt-3">
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
