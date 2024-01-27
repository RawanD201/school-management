<header class="flex  flex-col gap-2 mb-4">
    <div class="flex w-full h-2">
        <div class="w-1/2 h-full !bg-[#0EA5E9] !print-color-exact"
            style="background-color: rgb(14, 165, 233) !important;-webkit-print-color-adjust: exact !important;print-color-adjust: exact !important; border-bottom: 16px solid rgb(14, 165, 233);">
        </div>
        <div class="w-1/2 h-full !bg-[#757070] !print-color-exact"
            style="background-color: rgb(117 112 112) !important;-webkit-print-color-adjust: exact !important;print-color-adjust: exact !important; border-bottom: 16px solid rgb(117 112 112);">
        </div>
    </div>

    <div class="flex items-center gap-4 container-content justify-between flex-row-reverse">

        <img class="w-24 h-12 object-contain" src="{{ asset('/img/dark-logo.png') }}" alt="logo">

        <div class="flex flex-col gap-1 content justify-self-center rtl:text-right">
            <span class="mt-4 text-2xl font-semibold uppercase">
                {{ __('labels.report.header.heading') }}
            </span>
            <span class="block text-justify uppercase" style=" width: 374px !important;">
                {{ __('labels.report.header.subheading') }}
            </span>
        </div>
    </div>
</header>
