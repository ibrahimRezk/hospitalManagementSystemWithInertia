import { ref, watch, computed } from "vue";
import { Inertia } from "@inertiajs/inertia"; 

export default function (params) {
  const { filters: defaultFilters, routeResourceName , method } = params;
// we have to convert filters to ref becase it is calling a v-model 
  const filters = ref(defaultFilters); 

  ///// we can use it like below but after refresh the page we lose the input 
  // const {  routeResourceName } = params;
  // const filters = ref({});
  

  const isFilled = computed(() => {
    let {page, ...rest} = filters.value /// page is the first parameter in addressbar after ? "[http://127.0.0.1:8000/admin/products?page=1&name=]" it will be alwayes there and have value because of pagination so we want to look at the second or third parameter or whatever  witch is ...rest  and apply filters.value to it and if it is there it will return true otherwise it will return false //// we did that to return false after we remove search input and refresh
    return Object.values(rest)
      .some(v => !["", null, undefined].includes(v)) 
  })

  const isLoading = ref(false);
  const fetchItemsHandler = ref(null); 

  function fetchItems() {
    Inertia.get(route(`admin.${routeResourceName}.${method}`), {
      ...filters.value,
      page:1
    }, {
      preserveState: true,
      preserveScroll: true,
      replace: true,
      onBefore: () => isLoading.value = true,
      onFinish: () => isLoading.value = false,
    });
  }

  watch(
    filters,
    () => {
      clearTimeout(fetchItemsHandler.value);

      fetchItemsHandler.value = setTimeout(() => {
        fetchItems();
      }, 300);
    },
    {
      deep: true,
    }
  );

  return {
    filters,
    isLoading,
    isFilled,
  }
}