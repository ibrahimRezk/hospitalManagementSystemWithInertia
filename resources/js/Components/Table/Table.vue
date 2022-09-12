<script setup>
import Th from "@/Components/Table/Th.vue";
import Td from "@/Components/Table/Td.vue";
import Pagination from "@/Components/Table/Pagination.vue";

defineProps({
    headers: {
        type: Array,
        default: () => [],
    },
    items: {
        type: Object,
        default: () => ({}),
    },
});
</script> 

<template>
    <div class="flex flex-wrap -mx-3  ">
            <div class="flex-none w-full max-w-full px-3 ">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-gray-50 border border-white border-solid shadow-soft-xl rounded-2xl bg-clip-border shadow-xl "
               >
               <slot name="section"/>
                <div class="flex-auto px-0 pt-0 pb-2 ">
                <div class="p-0 overflow-x-auto">
                <!-- <div class="p-0 "> -->
                <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500  ">
                    <thead class="align-bottom bg-gray-600 ">
                    
                        <tr>
                            <Th v-for="header in headers"
                                :key="header.label"
                                :class="`${header.classes} font-bold text`"
                                >
                                
                                {{ header.label }}
                            </Th>
                        </tr>
                    </thead>
                    <tbody > 
                        <tr class=" hover:bg-zinc-200 "
                        
                            v-for="item in items.data"
                            :key="item.id">
                            

                            <slot :item="item"></slot>

                        <slot name="item" :item="item"></slot>

                        </tr>
                        <tr v-if="items.data.length === 0" >
                            <Td :colspan="headers.length">
                                No data available.
                            </Td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div v-if="items.meta.links.length > 3" class=" py-2">


        <Pagination :links="items.meta.links"/>



        </div>
    </div>
    </div>
    </div>
</template>