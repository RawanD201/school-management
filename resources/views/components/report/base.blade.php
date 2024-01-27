@props([
    'header' => null,
    'title' => null,
    'pageHeader' => null,
    'summary' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ __('filament::layout.direction') ?? 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="{{ asset('css/print.css') }}" rel="stylesheet">
    <title>{{ $title }}</title>


    {{ $header }}
</head>

<body>
    <x-report.header />

    <div class="my-4 text-2xl">
        {{ $pageHeader }}
    </div>

    {{ $slot }}


    @if ($summary)
        <hr style=" width: 100%; height: 2px; color: #0EA5E9; background-color: #0EA5E9;">

        <div style="display: flex; align-items:center; justify-content: space-between; gap:2px; width: 100%;">
            <div style="display: flex; flex-direction: column; width: 50%; gap: 2px;">
                <div>
                    <span
                        style="background:#757070; padding: 0px 10px; color: white;">{{ __('labels.report.amount') . ': ' }}</span>
                    <span
                        style="font-weight: 800; padding: 0px 10px;">{{ number_format($summary['amount']) . ' ' . __('iqd') }}</span>
                </div>

            </div>
        </div>
    @endif


    <x-report.footer />

</body>

</html>
