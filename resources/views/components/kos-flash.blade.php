@if (session('ok'))
    <div class="flash" role="status">{{ session('ok') }}</div>
@endif
@if ($errors->any())
    <div class="err" role="alert">
        <strong>Periksa kembali isianmu.</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
