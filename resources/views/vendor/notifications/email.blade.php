<style>
    h1 {
        color: #4f46e5 !important;
    }

    p.im, span.im {
        color: #64748b !important;
    }

    table > tr > td a {
        color: #4f46e5 !important;
    }

    .button {
        color: #ffffff !important;
        background-color: #4f46e5 !important;
        border-bottom: 8px solid #4f46e5 !important;
        border-left: 18px solid #4f46e5 !important;
        border-right: 18px solid #4f46e5 !important;
        border-top: 8px solid #4f46e5 !important;
    }
</style>

<x-mail::message>
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Hello!')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
С наилучшими пожеланиями,<br>
команда {{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
Если у вас возникли проблемы с нажатием кнопки "{{ $actionText }}",
скопируйте и вставьте приведенный ниже URL-адрес в свой веб-браузер:
<span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
