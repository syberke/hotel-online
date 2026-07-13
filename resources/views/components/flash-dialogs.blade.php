@php
    $flashDialogs = [
        'success' => session('success'),
        'error' => session('error'),
        'info' => session('info'),
    ];

    if (session('status') && session('status') !== 'profile-updated') {
        $flashDialogs['success'] = session('status');
    }

    if ($errors->any()) {
        $flashDialogs['error'] = $errors->first();
    }
@endphp

@foreach($flashDialogs as $type => $message)
    @if($message)
        <span hidden data-oasis-flash data-type="{{ $type }}" data-message="{{ $message }}"></span>
    @endif
@endforeach
