<input id="{{ $getStatePath() }}" type="hidden" {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}">

<x-filament::modal id="preview-modal" width="7xl" display-classes="block" x-init="$wire.on('open-preview-modal-{{ $getUniqueActionId() }}', function() {
    triggerInputEvent('{{ $getStatePath() }}', '{{ $shouldRefresh() ? 'refresh' : '' }}');
    isOpen = true;
});
$wire.on('close-preview-modal-{{ $getUniqueActionId() }}', () => { isOpen = false; });" :heading="$getPreviewModalHeading()">
    <div class="space-y-4 preview-table-wrapper">
        <x-report.header />


        @php
            $summary = method_exists($this, 'getSummary');
        @endphp


        <div class="my-8 text-2xl font-nrt">
            {{ $this->getTitle() }}
        </div>

        <table class="my-8 preview-table" x-init="$wire.on('print-table-{{ $getUniqueActionId() }}', function() {
            triggerInputEvent('{{ $getStatePath() }}', 'print-{{ $getUniqueActionId() }}')
        })">
            <tr class="text-center bg-[#0EA5E9] ">
                @foreach ($getAllColumns() as $column)
                    <th class="!font-bold text-center text-white">
                        {{ $column->getLabel() }}
                    </th>
                @endforeach
            </tr>
            @foreach ($getRows() as $row)
                <tr>
                    @foreach ($getAllColumns() as $column)
                        <td @class(['text-center'])>
                            {{ $row[$column->getName()] }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </table>


        @if ($summary)
            <div>
                <span
                    style="background:#757070; padding: 0px; color: white;">{{ __('labels.report.amount') . ': ' }}</span>
                <span
                    style="font-weight: 800; padding: 0px 10px;">{{ number_format($this->amount) . '   ' . __('iqd') }}</span>
            </div>
            {{-- <div style="margin: 0px;">
                <span
                    style="background:#757070; padding: 0px 10px; color: white;">{{ __('labels.report.dollar_amount') . ': ' }}</span>
                <span
                    style="font-weight: 800; padding: 0px 10px;">{{ number_format($this->dollar_amount, 3) . '   ' . '$' }}</span>
            </div> --}}
        @endif

        <x-report.footer />


        <div>
            <x-tables::pagination :paginator="$getRows()" :records-per-page-select-options="$this->getTable()->getRecordsPerPageSelectOptions()" />
        </div>
    </div>
    <x-slot name="footer">
        @foreach ($getFooterActions() as $action)
            {{ $action }}
        @endforeach
    </x-slot>
    @php
        $data = $this->mountedTableBulkAction ? $this->mountedTableBulkActionData : $this->mountedTableActionData;
    @endphp
    @if (is_array($data) && array_key_exists('table_view', $data) && $data['table_view'] == 'print-' . $getUniqueActionId())
        <script>
            printHTML(`{!! $this->printHTML !!}`, '{{ $getStatePath() }}', '{{ $getUniqueActionId() }}');
        </script>
    @endif
    @if ($shouldRefresh())
        <script>
            window.Livewire.emit("close-preview-modal-{{ $getUniqueActionId() }}");

            triggerInputEvent('{{ $getStatePath() }}', 'refresh');

            window.Livewire.emit("open-preview-modal-{{ $getUniqueActionId() }}");
        </script>
    @endif
</x-filament::modal>
