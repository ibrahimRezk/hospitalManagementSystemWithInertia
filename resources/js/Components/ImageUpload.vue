<script setup>
import { onMounted } from "@vue/runtime-core";
import { usePage } from "@inertiajs/inertia-vue3";
import Dropzone from "dropzone";
import "dropzone/dist/dropzone.css"

const props = defineProps({
    item: {
        type: Object,
        default: () => { },
    },
    modelType: {
        type: String,
        required: true,
    },
    modelId: {
        type: Number,
        required: true,
    },
    maxFilesize: {
        type: Number,
        default: 1024,
    },
    maxFiles: {
        type: Number,
        default: 10,
    },
});


onMounted(() => {
    Dropzone.autoDiscover = false;
    let dropzone = new Dropzone("#image-upload", {
        url: route("admin.images.store"),
        headers: {
            "X-CSRF-TOKEN": usePage().props.value.auth.token
            // "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token'] ?.content,") this will not work becase it brings the token from the last session not from currnt one 
        },
        paramName: "image",
        maxFilesize: props.maxFilesize,
        maxFiles: props.maxFiles,
        // acceptedFiles: ".jpeg, .jpg, .png",
        addRemoveLinks: true,

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // params: {
        //             _token: '{{ csrf_token() }}'
        //         },
        acceptedFiles: 'image/*',
        dictDefaultMessage: 'اضغط هنا لرفع الملفات او قم بسحب الملفات  وافلاتها هنا',
        dictFallbackMessage: " المتصفح الخاص بكم لا يدعم خاصيه تعدد الصوره والسحب والافلات ", // emam
        dictInvalidFileType: "لايمكنك رفع هذا النوع من الملفات ", // emam
        dictCancelUpload: "الغاء الرفع ", // emam
        dictCancelUploadConfirmation: " هل انت متاكد من الغاء رفع الملفات ؟ ", // emam
        dictMaxFilesExceeded: "لايمكنك رفع عدد اكثر من هذا  ", // emam

        ///////////// new ///////////////////////////////
        dictFallbackText: "Please use the fallback form below to upload your files like in the olden days.",
        dictFileTooBig: "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.",
        dictResponseError: "Server responded with {{statusCode}} code.",
        dictRemoveFile: "حذف",
        //////////////////////////////////////////////////////////////////////

        removedfile: function (file) {
            file.previewElement.remove(); // emam
            axios.delete(route('admin.images.destroy', { id: file.image_id }));
            return

        },

        init: function () {

            props.item.images?.forEach((image) => {
                var mock = {
                    name: image.img.name,
                    image_id: image.img.id,
                    size: image.img.size,
                    type: image.img.mime_type,
                };
                this.emit("addedfile", mock);

                // images.img comming from laboratory resourcey
                this.options.thumbnail.call(this, mock, image.img.original_url);   // this will get the original image 

                let dzProgress = document.getElementsByClassName('dz-progress');
                dzProgress[0].classList.remove('dz-progress')
            },

                // this must be inside forEach but must be outside the callback function of foreach    .... basicaly between }, of the callback function  and ) of the foreach;
                this.on("addedfile", function (file) {
                    console.log(file)
                    file.previewElement.addEventListener("click", function () {
                        console.log('hi')
                        window.open(file.previewElement.children[0].children[0].currentSrc, '_blank');
                    });
                })
            );

        },

    });

    dropzone.on("sending", (file, xhr, formData) => {
        formData.append("modelType", props.modelType);
        formData.append("modelId", props.modelId);
    });
    dropzone.on("success", function (file, response) {
        file.image_id = response.id;
    });


    // Jkh6xlVBVhfjYBwt4beUuwUHHmqwszi3WC5LyoO8



});
</script>
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////// important code to move the error messages under the images down alittle
bit to show remove link
<style>
.dropzone .dz-preview .dz-error-message {
    top: 150px !important;
}
</style>
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

<template>
    <div class="dropzone mt-3 shadow-xl bg-white" id="image-upload">
        <div v-if="props.item.images?.length < 1 ">
            <div class="dz-message" data-dz-message>
                <div>
                    Drop Image<span v-if="maxFiles > 1">s</span> here to upload
                </div>
                <div>
                    You can only upload {{ maxFiles }} image<span v-if="maxFiles > 1">s</span>
                </div>
            </div>
        </div>
    </div>
</template>
