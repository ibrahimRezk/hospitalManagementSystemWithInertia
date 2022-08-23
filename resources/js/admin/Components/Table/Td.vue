<script setup>



</script>




<template>
    <!-- <td
        class="py-1 px-4 capitalize border-b-solid tracking-none whitespace-nowrap text-slate-700 opacity-70"
    > -->
    <td
        class=" px-4  py-1 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-sm text-gray-500 drop-shadow-lg ">

        <div class=" flex px-2 py-1  ">  
         <!-- ////////////////////////////////////////// remember here and  add text-center to yes no label  -->
        <!-- <div class="">  -->

                            <slot name="image"/>
                                

                            <div class=" flex-col justify-center  ">
                                <h6 class=" mb-0 leading-normal text-size-sm ">
                                <slot />
                                </h6>
                                <p class="mb-0 leading-tight text-size-xs text-slate-400"> 
                                <slot name="second-line"/>
                                </p>
                            </div>


            
        </div>
    </td>
</template>
