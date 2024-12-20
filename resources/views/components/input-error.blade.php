@props(['messages'])

@if ($messages)
        @foreach ((array) $messages as $message)
        <div {{ $attributes->merge(['class' => 'form-text text-danger']) }}>{{ $message }}</div>
        @endforeach
@endif
