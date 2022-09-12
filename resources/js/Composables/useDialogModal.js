import {ref} from "vue";
import {Inertia} from "@inertiajs/inertia";

export default function (params) {
    const {
        routeResourceName,
        form: formItems,
        opened: openedMenu,
        showScreenExeptSubmenu, 
        method

    } = params;
    const form = ref(formItems)
    const opened = ref(openedMenu)

    const dialogModal = ref(false);
    const itemToSave = ref({});
    const isSaving = ref(false);

    function closeDialogModal() {
        dialogModal.value = false;
        itemToSave.value = {};
        opened.value = 0
        showScreenExeptSubmenu.value =false
    }

    function showDialogModal(item) {
        dialogModal.value = true;
        itemToSave.value = item;
    }

 // here we use inertia.post not form.post because we send extra data and we check for errors with another way  with props.errors
    function handleSavingItem() {
        Inertia.post(
            route(`doctor.${routeResourceName}.${method}`), {
                id: itemToSave.value.id,
                ...form.value,
                 data: itemToSave.value
            }, {
                preserveScroll: true,
                preserveState: true,
                onBefore: () => {
                    isSaving.value = true;
                },
                onSuccess: () => {
                    dialogModal.value = false;
                    itemToSave.value = {};
                    opened.value = 0;  // to close
                    showScreenExeptSubmenu.value =false;
                    form.value.reset() // important to clear all form data

                },
                onFinish: () => {
                    isSaving.value = false;

                },
            }
        );
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
