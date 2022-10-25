<script setup>
// import imagePath from '../../../public/frontend/images/bg/banner.jpg'

</script>

<template>
    <!-- <div :style="{ backgroundImage: `url(${imagePath} )` }">
        <img src="#" width="500" height="600" alt="">
    </div> -->

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-no-repeat bg-cover bg-gray-100 bg-[url('../../../public/frontend/images/bg/banner.jpg')]"
    >
        <div>
            <slot name="logo" />
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <slot />
        </div>
    </div>
</template>
