import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia"; 

export default function (params) {
  const { routeResourceName } = params;

  const deleteModal = ref(false);
  const itemToDelete = ref({});
  const isDeleting = ref(false);
  
  function close(){
    deleteModal.value = false;
    itemToDelete.value = {};
  }

  function showDeleteModal(item) {
    deleteModal.value = true; 
    itemToDelete.value = item;
  }

  function handleDeleteItem() {
    Inertia.delete(
      route(`admin.${routeResourceName}.destroy`, { id: itemToDelete.value.id }),
      {
        preserveScroll: true,
        preserveState: true,
        onBefore: () => {
          isDeleting.value = true;
        },
        onSuccess: () => {
          deleteModal.value = false;
          itemToDelete.value = {};
          
            Toast.fire({
                icon: "success",
                title: "Item Deleted successfully",
                iconColor: 'white',
                color:'black',  // text color
                // background: '#1cac78        ', // green
                // background: '#00a877       ', // green
                // background: '#39ff14   ', // lime
                background: '#dc143c    ', // red
            });
          
        },
        onFinish: () => {
          isDeleting.value = false;
        },
      }
    );
  }

  return {
    deleteModal,
    itemToDelete,
    isDeleting,
    showDeleteModal,
    handleDeleteItem,
    close
  }
}
