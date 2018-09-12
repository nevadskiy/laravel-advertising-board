@foreach([
    'status'  => 'success',
    'success' => 'success',
    'info'    => 'info',
    'error'   => 'danger'
] as $key     => $class)

    @if (session($key))
        <div class="alert alert-{{ $class }}" role="alert">
            {{ session($key) }}
        </div>
    @endif

@endforeach