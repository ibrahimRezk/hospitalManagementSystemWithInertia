<script setup>
import { Inertia } from  "@inertiajs/inertia";

defineProps({
    links: Array,
    simpleLinks: Array,
});

function goToUrl(link){
    Inertia.get(link.url)
}

function isDisabled(link){
    return link.url == null|| link.active;
}

</script>

<template>
    <!-- <div class="flex justify-center"> -->
    <nav aria-label="Page navigation example" class="items-center">
        <ul class="flex  list-style-none ">
            <li class="flex-nowrap page-item border rounded-sm hidden md:block"
            v-for="link in links" :key="link.label">
                <button
                    class="page-link relative block py-1.5 px-3   bg-transparent outline-none transition-all duration-300 rounded    hover:text-gray-800 hover:bg-gray-200 focus:shadow-none "
                    
                    :class="{
                        'text-gray-300 cursor-not-allowed': isDisabled(link),
                        'text-gray-500 ': !isDisabled(link),
                        'bg-blue-800 font-bold':link.active 
                    }
                        "
                    v-html="link.label"
                    @click.prevent="goToUrl(link)"
                    :disabled ="isDisabled(link)"
                    >
                    
                    </button
                >
            </li>
            <li class="flex-nowrap page-item border rounded-sm md:hidden "
            v-for="link in links" :key="link.label">
                <button
                v-if="link.label.includes('&')"
                    class="page-link relative block py-1.5 px-3   bg-transparent outline-none transition-all duration-300 rounded    hover:text-gray-800 hover:bg-gray-200 focus:shadow-none "
                    
                    :class="{
                        'text-gray-300 cursor-not-allowed': isDisabled(link),
                        'text-gray-500 ': !isDisabled(link),
                        'bg-blue-800 font-bold':link.active 
                    }
                        "
                    v-html="link.label"
                    @click.prevent="goToUrl(link)"
                    :disabled ="isDisabled(link)"
                    >
                    
                    </button
                >
            </li>


           
            
        </ul>
    </nav>
    <!-- </div> -->
</template>
