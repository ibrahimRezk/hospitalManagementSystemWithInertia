import {ref} from "vue";
import {Inertia} from "@inertiajs/inertia";

export default function (params) {
    const {
        routeResourceName: theRouteResourceName,
        form: formItems,
        opened: openedMenu,
        showScreenExeptSubmenu, 
        method : calledMethod,
        editMode : editModeValue,
        invoice_status: invoice_status_value,

    } = params;
    const form = ref(formItems)
    const opened = ref(openedMenu)
    const method = ref(calledMethod)
    const routeResourceName = ref(theRouteResourceName)
    const editMode = ref(editModeValue)
    const invoice_status = ref(invoice_status_value)

 
    const dialogModal = ref(false);
    const itemToSave = ref({});
    const isSaving = ref(false);

    function closeDialogModal() {
        dialogModal.value = false;
        itemToSave.value = {};
        opened.value = 0
        showScreenExeptSubmenu.value = false
    }

    function showDialogModal(item) {
        dialogModal.value = true;
        itemToSave.value = item;
    }

 // here we use inertia.post not form.post because we send extra data and we check for errors with another way  with props.errors
 // important   ,in update  we put route with id in section 1 () and data in section 2 {} and options in section 3
    function handleSavingItem() {
        if(editMode.value == true ) 
        {
            Inertia.put(
                route(`doctor.${routeResourceName.value}.${method.value}`, {
                    id: itemToSave.value.id,
                }), 
                    {
                    ...form.value,
                    data: itemToSave.value, 
                    invoice_status : invoice_status.value
                    },
                    {
                    preserveScroll: true,
                    preserveState: true,
                    onBefore: () => {
                        isSaving.value = true;
                    },
                    onSuccess: () => {
                        dialogModal.value = false;
                        itemToSave.value = {};
                        opened.value = 0;  // to close   // no need any more
                        showScreenExeptSubmenu.value =false;
                        form.value.reset() // important to clear all form data

                        
                                Toast.fire({
                                    icon: "success",
                                    title: "Item Updated successfully",
                                    iconColor: 'white',
                                    color:'black',  // text color
                                    background: '#6699ff', // blue        ', // green
                                    // background: '#00a877       ', // green
                                    // background: '#39ff14   ', // lime
                                    // background: '#dc143c    ', // red
                                });
                    },
                    onFinish: () => {
                        isSaving.value = false;
    
                    },
                }
            )
            

        }else{
            console.log(false)
            Inertia.post(
                route(`doctor.${routeResourceName.value}.${method.value}`), {
                    id: itemToSave.value.id,
                    ...form.value,
                     data: itemToSave.value,
                     invoice_status : invoice_status.value

                }, {
                    preserveScroll: true,
                    preserveState: true,
                    onBefore: () => {
                        isSaving.value = true;
                    },
                    onSuccess: () => {
                        Toast.fire({
                            icon: "success",
                            title: "Item Updated successfully",
                            iconColor: 'white',
                            color:'black',  // text color
                            background: '#6699ff        ', // blue
                            // background: '#00a877       ', // green
                            // background: '#39ff14   ', // lime
                            // background: '#dc143c    ', // red
                        });
                        
                        dialogModal.value = false;
                        itemToSave.value = {};
                        // opened.value = 0;  // to close   // no need any more
                        showScreenExeptSubmenu.value =false;
                        form.value.reset() // important to clear all form data
    
                    },
                    onFinish: () => {
                        isSaving.value = false;
    
                    },
                }
            );
        }
        
    }

    return {
        closeDialogModal,
        dialogModal,
        itemToSave,
        isSaving,
        showDialogModal,
        handleSavingItem
    }
}
