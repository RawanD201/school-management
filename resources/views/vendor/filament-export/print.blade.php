{{-- <x-report.base :title="$fileName ?? date()->format('d')" :pageHeader="$getPageHeader()"> --}}
@props([
    'summary' => \array_key_invoke($data, 'getSummary'),
])

<x-report.base :title="$fileName ?? date()->format('d')" :summary="$summary">

    <x-slot name='header'>

        <style type="text/css" media="all">
            table {
                background: white;
                color: black;
                width: 100%;
                border-collapse: collapse;
                border-spacing: 0;
                font-family: sans-serif;
            }

            td,
            th {
                border-color: #ededed;
                border-style: solid;
                border-width: 1px;
                font-size: 13px;
                line-height: 2;
                overflow: hidden;
                padding-left: 6px;
                word-break: normal;
            }

            th,
            .td-field-summary {
                font-size: 0.9rem;
                font-weight: bold;
            }

            table {
                page-break-after: auto
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto
            }

            td {
                page-break-inside: avoid;
                page-break-after: auto
            }
        </style>
    </x-slot>

    <x-report.table :columns="$columns" :rows="$rows" />

</x-report.base>
