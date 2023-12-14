<div class="grid gap-[27px]">
    @foreach (range(1, 5) as $i)
        <div class="grid">
            <div class="shimmer w-[150px] h-[17px]"></div>

            <div class="flex gap-5 items-center">
                <div class="shimmer w-full h-2"></div>

                <div class="shimmer w-[35px] h-[17px]"></div>
            </div>
        </div>
    @endforeach

    <div class="flex gap-5 justify-end">
        <div class="flex gap-1 items-center">
            <div class="shimmer w-3.5 h-3.5 rounded-[3px]"></div>
            <div class="shimmer w-[143px] h-[17px]"></div>
        </div>
    </div>
</div>