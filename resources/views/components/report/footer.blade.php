<footer class="flex justify-start gap-2 mt-8 rtl:flex-row-reverse rtl:justify-end">


    <div class="flex items-center gap-4">

        <div class="flex flex-col w-2 h-full" style="width:8px;">
            <div class="w-full h-1/2 !bg-[#0EA5E9]  !print-color-exact"
                style="background-color: rgb(14, 165, 233) !important;-webkit-print-color-adjust: exact !important;print-color-adjust: exact !important;">
            </div>
            <div class="w-full h-1/2 !bg-[#757070]  !print-color-exact"
                style="background-color: rgb(117 112 112) !important;-webkit-print-color-adjust: exact !important;print-color-adjust: exact !important;">
            </div>
        </div>


        <div class="flex flex-col gap-1 text-left rtl:text-right justify-self-center">
            <span class="block">
                {{ __('labels.report.footer.address') }}
            </span>
            <span class="block">
                {{ __('labels.report.footer.tel') }}
            </span>
            <span class="block">
                {{ __('labels.report.footer.email') }}
            </span>
            <span class="block">
                {{ __('labels.report.footer.web') }}
            </span>
        </div>


    </div>
</footer>
