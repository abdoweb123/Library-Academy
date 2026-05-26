<style>
    /* HTML: <div class="loader"></div> */
</style>
{{--<div class="loader" id="global-loader"></div>--}}


<div class="Global-Loader" id="global-loader" style="z-index: 999999999">
    <img style="width: 150px;" src="{{ getFile(isset($setting->where('key','logo')->first()->value) ? $setting->where('key','logo')->first()->value : 'assets/uploads/empty.png')}}" class="loader-img" alt="Loader">
</div>
