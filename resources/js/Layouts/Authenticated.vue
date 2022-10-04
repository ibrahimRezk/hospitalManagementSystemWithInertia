<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { onMounted, ref, watch, computed } from "vue";
import { trans } from "laravel-vue-i18n";
import { getActiveLanguage, isLoaded } from "laravel-vue-i18n";
import { usePage } from "@inertiajs/inertia-vue3";
import { loadLanguageAsync } from "laravel-vue-i18n";
import SidebarIcon from "../Components/Icons/SidebarIcon.vue";
// import JetDropdown from "@/components/Dropdown.vue";
import JetDropdown from "../Components/Dropdown.vue";
import JetDropdownLink from "@/components/DropdownLink.vue";
import Translations from "@/Components/translations/Translations.vue";

const current_lang = document
    .getElementsByTagName("html")[0]
    .getAttribute("lang");

watch(
    () => trans("home.sitelang"),
    () => {
        document.getElementsByTagName("html")[0].getAttribute("lang") == "ar"
            ? document
                  .getElementsByTagName("html")[0]
                  .setAttribute("dir", "rtl")
            : document
                  .getElementsByTagName("html")[0]
                  .setAttribute("dir", "ltr");

        const lang = getActiveLanguage();

        Inertia.get(route("admin.lang", [lang]));
    }
);

const direction = computed(() => {
    if (document.getElementsByTagName("html")[0].getAttribute("lang") == "ar")
        return "left";
    else return "right";
});

const showHideSidebar = ref(false);

const showHideClass = computed(() => {
    if (showHideSidebar.value) {
        return "ltr:-translate-x-0 rtl:translate-x-0 shadow-soft-xl  z-990";
    } else {
        return "";
    }
});

///////////////////////////////////////// pusher notifications /////////////////////////////////////////////
const notificationsCount = ref();
notificationsCount.value = parseInt(usePage().props.value.notificationsCount);
const notifications = usePage().props.value.notifications;

const showNewMessage = ref(false);
const open = ref(false);
const items = ref([]);
const patientDetails = ref();

const userId = usePage().props.value.user.id;

Echo.private(`create-invoice.${userId}`).listen(".create-invoice", (e) => {
    showNewMessage.value = true;
    items.value.push(e);
    open.value = true;
    setTimeout(() => (open.value = false), 4000);
    notificationsCount.value += 1;
    patientDetails.value = `/doctor/patient_details/${e.patient_id} `;
});
///////////////////////////////////////////////////////////////////////////////////////////////////

const sidebarMenuTextColor = computed(() => {
    return "text-slate-400  hover:text-slate-600";
});

////// to open submenu and close other submenus in the same time use this /////////////////////////
/////////////////remember to add function openCloseSubMenu(menu) in template /////////////////////
const openCloseSubMenu = (menu) => {
    const submenuValu = ref(menu.open);

    usePage().props.value.menus.forEach((menu) => {
        if (menu.hasSubmenu) {
            menu.open = false;
            submenuValu.value = !submenuValu.value;
        }
    });

    return submenuValu.value ? (menu.open = true) : (menu.open = false);
};
////////////////////////////////////////////////////////////////
//// otherwise just use onClick = "menu.open != menu.open" in template
////////////////////////////////////////////////////////////////

// important
// z-990 in class in template down can cause apperance of sidebar items in white above all items
</script>

<style scoped>
.default-header {
    background-color: rgb(26, 49, 75);
    text-transform: uppercase;
}

/* base */
.fade {
    backface-visibility: hidden;
    z-index: 1;
}

/* moving */
.fade-move {
    transition: all 600ms ease-in-out 20ms;
}

/* appearing */
.fade-enter-active {
    transition: all 400ms ease-out;
}

/* disappearing */
.fade-leave-active {
    transition: all 200ms ease-in;
    position: absolute;
    z-index: 0;
}

/* appear at / disappear to */
.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>

<template>
    <body
        class="m-0 font-sans antialiased font-normal text-size-base leading-default text-slate-500"
    >
        <div
            v-show="showHideSidebar"
            class="z-990 fixed inset-0 transform transition-all"
            @click="showHideSidebar = false"
        >
            <div class="absolute inset-0 bg-gray-500 opacity-75" />
        </div>

        <!-- sidenav  -->
        <aside
            :class="showHideClass"
            class="rtl:translate-x-full ltr:-translate-x-full rtl:xl:translate-x-0 ltr:xl:-translate-x-0 ltr:xl:left-0 w-64 ease-nav-brand fixed inset-y-0 mt-1 ltr:ml-0 rtl:mr-0 block flex-wrap items-center justify-between overflow-y-auto border border-slate-300 bg-white p-0 antialiased shadow-none transition-transform duration-200 rtl:xl:right-0 xl:translate-x-0 xl:bg-transparent ps"
        >
            <div
                class="h-full items-center block w-auto overflow-auto grow basis-full bg-slate-900 2xl shadow-xl"
            >
                <div class="h-30 bg-white">
                    <i
                        class="absolute top-0 right-0 hidden p-4 opacity-50 cursor-pointer fas fa-times text-slate-400 xl:hidden"
                        sidenav-close
                    ></i>
                    <a
                        class="block px-8 py-1 m-0 text-size-sm whitespace-nowrap text-slate-700"
                        href="#"
                    >
                        <img
                            src="../../../public/admin/assets/img/logo-ct.png"
                            class="inline h-full max-w-full transition-all duration-200 ease-nav-brand max-h-24"
                            alt="main_logo"
                        />
                        <!-- <span
                            class1="rtl:ml-1 ltr:mr-1 font-semibold transition-all duration-200 ease-nav-brand"
                            class="mr-1 font-semibold transition-all duration-200 ease-nav-brand"
                            classrtl="mr-1"
                            classltr="ml-1">
                            >Soft UI Dashboard</span 
                        > -->
                    </a>
                </div>

                <hr
                    class="h-px mt-0 bg-transparent bg-gradient-horizontal-dark"
                />
                <ul class="flex flex-col pl-0 mb-0">
                    <!-- sidebar links -->
                    <li class="mt-0.5 w-full">
                        <div
                            v-show="menu.isVisible"
                            v-for="menu in $page.props.menus"
                            :key="menu.label"
                            :active="menu.isActive"
                        >
                            <!-- //////////////////////////////////////////////////menus with sub menu/////////////////////////////////////////// -->
                            <div v-if="menu.hasSubmenu">
                                <transition-group duration="550" name="fade">
                                    <div
                                        :class="sidebarMenuTextColor"
                                        class="transition"
                                    >
                                        <button
                                            :ref="'header-' + menu.label"
                                            class="w-56 flex items-center justify-between rounded-md hover:bg-slate-200 === ion-padding default-header ======"
                                            @click="openCloseSubMenu(menu)"
                                            :class="
                                                menu.isActive
                                                    ? ' py-2.7 shadow-soft-xl m-2 text-size-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-white  px-4 font-semibold text-slate-700 transition-colors'
                                                    : 'py-2.7 text-size-sm ease-nav-brand my-0 mx-4 flex   items-center whitespace-nowrap px-4 transition-colors'
                                            "
                                        >
                                            <SidebarIcon
                                                :active="menu.isActive"
                                                :icon="menu.label"
                                            />
                                            <span
                                                class="w-full flex items-center justify-between"
                                            >
                                                <span
                                                    class="mr-1 duration-300 opacity-100 pointer-events-none ease-soft first-line:"
                                                >
                                                    <Translations
                                                        :label="menu.label"
                                                    />
                                                </span>

                                                <span>
                                                    <svg
                                                        v-show="!menu.open"
                                                        class="h-5 w-5"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20"
                                                        fill="currentColor"
                                                        aria-hidden="true"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                            clip-rule="evenodd"
                                                        />
                                                    </svg>

                                                    <svg
                                                        v-show="menu.open"
                                                        class="h-5 w-5"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20"
                                                        fill="currentColor"
                                                        aria-hidden="true"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z"
                                                            clip-rule="evenodd"
                                                        />
                                                    </svg>
                                                </span>
                                            </span>
                                        </button>
                                    </div>

                                    <!-- //////////////////////////////////////////////////submenus/////////////////////////////////////////// -->

                                    <div class="translation-action">
                                        <div
                                            :class="sidebarMenuTextColor"
                                            v-show="menu.open"
                                            v-for="(
                                                submenu, index
                                            ) in menu.subMenus"
                                            :key="index"
                                            class="px-4"
                                            id="filter-section-mobile-1"
                                        >
                                            <Link
                                                class="hover:bg-slate-100 rounded-xl rtl:pr-6 ltr:pl-6 mt-2 py-1"
                                                :class="
                                                    submenu.isActive
                                                        ? ' shadow-soft-xl m-0 text-size-sm ease-nav-brand my-0  flex justify-between whitespace-nowrap  bg-slate-200 font-semibold text-slate-700 transition-colors'
                                                        : ' text-size-sm ease-nav-brand my-0  flex  justify-between  whitespace-nowrap transition-colors bg-slate-700 '
                                                "
                                                :href="submenu.url"
                                            >
                                                <SidebarIcon
                                                    :active="submenu.isActive"
                                                    :icon="submenu"
                                                />

                                                <span
                                                    class="w-full flex items-center justify-between"
                                                >
                                                    <span
                                                        class="mr-1 duration-300 opacity-100 pointer-events-none ease-soft"
                                                    >
                                                        <Translations
                                                            :label="
                                                                submenu.label
                                                            "
                                                            :active="
                                                                submenu.isActive
                                                            "
                                                        />
                                                    </span>
                                                </span>
                                            </Link>
                                            <hr
                                                class="h-px mt-0 bg-transparent bg-gradient-horizontal-dark"
                                            />
                                        </div>
                                    </div>
                                </transition-group>
                                <hr
                                    class="h-px mt-0 bg-transparent bg-gradient-horizontal-dark"
                                />
                            </div>

                            <!-- //////////////////////////////////////////////////menus without sub menu /////////////////////////////////////////// -->
                            <div v-else :class="sidebarMenuTextColor">
                                <Link
                                    class="w-56 flex items-center justify-between rounded-md hover:bg-slate-100"
                                    :class="
                                        menu.isActive
                                            ? ' py-2.7 shadow-soft-xl m-2 text-size-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-slate-200 px-4 font-semibold text-slate-700 transition-colors'
                                            : 'py-2.7 text-size-sm ease-nav-brand my-0 mx-4 flex   items-center whitespace-nowrap px-4 transition-colors '
                                    "
                                    :href="menu.url"
                                >
                                    <SidebarIcon
                                        :active="menu.isActive"
                                        :icon="menu.label"
                                    />
                                    <span
                                        class="w-full flex items-center justify-between"
                                    >
                                        <span
                                            class="mr-1 duration-300 opacity-100 pointer-events-none ease-soft"
                                        >
                                            <!-- test -->
                                            <Translations
                                                class="hover:text-red-500"
                                                :label="menu.label"
                                                :active="menu.isActive"
                                            />
                                        </span>
                                    </span>
                                </Link>
                                <hr
                                    class="h-px mt-0 bg-transparent bg-gradient-horizontal-dark"
                                />
                            </div>
                            <hr
                                class="h-px mt-0 bg-transparent bg-gradient-horizontal-dark"
                            />
                        </div>
                    </li>

                    <li class="xl:hidden"></li>
                </ul>
            </div>
        </aside>

        <main
            class1="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200"
            class="ltr:xl:ml-64 rtl:xl:mr-64 ease-soft-in-out relative h-full max-h-screen rounded-xl transition-all duration-200"
            classltr="xl:ml-64"
            classrtl="xl:mr-64 "
        >
            <!-- Navbar -->
            <nav
                class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all shadow-none duration-250 ease-soft-in rounded-2xl lg:flex-nowrap lg:justify-start"
                navbar-main
                navbar-scroll="true"
            >
                <div
                    class="flex items-center w-full justify-between px-4 py-1 mx-auto flex-wrap-inherit"
                >
                    <nav>
                        <!-- breadcrumb -->
                        <ol
                            class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16"
                        >
                            <li class="leading-normal text-size-sm">
                                <a
                                    class="opacity-50 text-slate-700"
                                    href="javascript:;"
                                    >Pages</a
                                >
                            </li>
                            <li
                                class1="text-size-sm pl-2 capitalize leading-normal text-slate-700 before:float-left before:pr-1 pl-1 before:text-gray-600 before:content-['/']"
                                class="text-size-sm pl-2 capitalize leading-normal text-slate-700 ltr:before:float-left rtl:before:float-right before:pl-1 pr-1 before:text-gray-600 before:content-['/']"
                                classltr="before:float-left"
                                classrtl="before:float-right"
                                aria-current="page"
                            >
                                {{ $page.props.currentPage }}
                            </li>
                        </ol>
                    </nav>

                    <div
                        class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto"
                    >
                        <div class="flex items-center md:ml-auto md:pr-4">
                            <div
                                class="relative flex flex-wrap items-stretch w-full transition-all rounded-lg ease-soft"
                            >
                                <span
                                    class="text-size-sm ease-soft leading-5.6 absolute z-50 -ml-px flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all"
                                >
                                    <i class="fas fa-search"></i>
                                </span>
                                <input
                                    type="text"
                                    class="pl-8.75 text-size-sm focus:shadow-soft-primary-outline ease-soft w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
                                    placeholder="Type here..."
                                />
                            </div>
                        </div>
                        <ul
                            class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full"
                        >
                            <!-- online builder btn  -->
                            <!-- <li class="flex items-center">
                    <a class="inline-block px-8 py-2 mb-0 mr-4 font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer leading-pro border-fuchsia-500 ease-soft-in text-size-xs hover:scale-102 active:shadow-soft-xs text-fuchsia-500 hover:border-fuchsia-500 active:bg-fuchsia-500 active:hover:text-fuchsia-500 hover:text-fuchsia-500 tracking-tight-soft hover:bg-transparent hover:opacity-75 hover:shadow-none active:text-white active:hover:bg-transparent" target="_blank" href="https://www.creative-tim.com/builder/soft-ui?ref=navbar-dashboard&amp;_ga=2.76518741.1192788655.1647724933-1242940210.1644448053">Online Builder</a>
                  </li> -->

                            <!-- <form @submit.prevent="logout">
                                                <JetDropdownLink as="button">
                                                    Log Out
                                                </JetDropdownLink>
                                            </form> -->
                            <li
                                class="flex items-center pl-4 xl:hidden"
                                @click="showHideSidebar = !showHideSidebar"
                            >
                                <a
                                    class="block p-0 transition-all ease-nav-brand text-size-sm text-slate-500"
                                >
                                    <div class="w-4.5 overflow-hidden">
                                        <i
                                            class="ease-soft mb-0.75 relative block h-0.5 rounded-sm bg-slate-500 transition-all"
                                        ></i>
                                        <i
                                            class="ease-soft mb-0.75 relative block h-0.5 rounded-sm bg-slate-500 transition-all"
                                        ></i>
                                        <i
                                            class="ease-soft relative block h-0.5 rounded-sm bg-slate-500 transition-all"
                                        ></i>
                                    </div>
                                </a>
                            </li>
                            <!-- <li class="flex items-center px-4">
                                    <a
                                        href="javascript:;"
                                        class="p-0 transition-all text-size-sm ease-nav-brand text-slate-500"
                                    >
                                        <i
                                            fixed-plugin-button-nav
                                            class="cursor-pointer fa fa-cog"
                                        ></i>
                                    </a>
                                </li> -->

                            <!-- languages -->

                            <!-- //////////////////////////////////////////////////////////////// -->
                            <li class="relative flex items-center pr-0">
                                <div class="sm:flex sm:items-center sm:ml-6">
                                    <div class="ml- relative">
                                        <JetDropdown
                                            :align="direction"
                                            width="40"
                                        >
                                            <template #trigger>
                                                <span
                                                    class="inline-flex rounded-md"
                                                >
                                                    <button
                                                        type="button"
                                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium text-gray-500 bg-transparent hover:text-gray-700 focus:outline-none transition"
                                                    >
                                                        <svg
                                                            class="rtl:ml-2 ltr:mr-2 -mr-0.5 h-4 w-4"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20"
                                                            fill="currentColor"
                                                        >
                                                            <path
                                                                fill-rule="evenodd"
                                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                clip-rule="evenodd"
                                                            />
                                                        </svg>

                                                        <img
                                                            v-if="
                                                                current_lang ==
                                                                'en'
                                                            "
                                                            src="../../../public/admin/assets/img/us-flag-icon.jpg"
                                                            class="inline-flex items-center justify-center ltr:mr-4 rtl:ml-4 text-white text-size-sm h-4 w-full max-w-none"
                                                        />

                                                        <img
                                                            v-if="
                                                                current_lang ==
                                                                'ar'
                                                            "
                                                            src="../../../public/admin/assets/img/eg-flag-icon.png"
                                                            class="inline-flex items-center justify-center ltr:mr-4 rtl:ml-4 text-white text-size-sm h-4 w-full max-w-none"
                                                            class1=" rounded-xl"
                                                        />

                                                        <!-- <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="h-6 w-6"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke="currentColor"
                                                                stroke-width="2"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"
                                                                />
                                                            </svg> -->
                                                    </button>
                                                </span>
                                            </template>

                                            <template #content>
                                                <!-- Account Management -->
                                                <div
                                                    class="block px-4 py-2 text-xs text-gray-400"
                                                >
                                                    Languages
                                                </div>
                                                <div
                                                    class="border-t border-gray-200"
                                                />
                                                <JetDropdownLink
                                                    as="button"
                                                    @click="
                                                        loadLanguageAsync('ar')
                                                    "
                                                >
                                                    <!-- add show class on dropdown open js -->

                                                    <div class="flex py-1">
                                                        <div class="my-auto">
                                                            <img
                                                                src="../../../public/admin/assets/img/eg-flag-icon.png"
                                                                class="inline-flex items-center justify-center ltr:mr-4 rtl:ml-4 text-white text-size-sm h-5 w-9"
                                                            />
                                                        </div>
                                                        <div
                                                            class="flex flex-col justify-center"
                                                        >
                                                            <h6
                                                                class="mb-1 font-normal leading-normal text-size-sm"
                                                            >
                                                                <span
                                                                    class="font-semibold"
                                                                    >العربية</span
                                                                >
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </JetDropdownLink>

                                                <JetDropdownLink
                                                    as="button"
                                                    @click="
                                                        loadLanguageAsync('en')
                                                    "
                                                >
                                                    <!-- add show class on dropdown open js -->

                                                    <div class="flex py-1">
                                                        <div class="my-auto">
                                                            <img
                                                                src="../../../public/admin/assets/img/us-flag-icon.jpg"
                                                                class="inline-flex items-center justify-center ltr:mr-4 rtl:ml-4 text-white text-size-sm h-5 w-9 max-w-none"
                                                            />
                                                        </div>
                                                        <div
                                                            class="flex flex-col justify-center"
                                                        >
                                                            <h6
                                                                class="mb-1 font-normal leading-normal text-size-sm"
                                                            >
                                                                <span
                                                                    class="font-semibold"
                                                                    >English</span
                                                                >
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </JetDropdownLink>

                                                <div
                                                    class="border-t border-gray-100"
                                                />

                                                <div
                                                    class="border-t border-gray-100"
                                                />
                                            </template>
                                        </JetDropdown>
                                    </div>
                                </div>
                            </li>

                            <!-- notifications -->

                            <li
                                class="relative flex items-center pr-2 dropdown-notifications show"
                            >
                                <div class="sm:flex sm:items-center sm:ml-6">
                                    <div class="ml-0 relative">
                                        <JetDropdown
                                            :align="direction"
                                            :open="open"
                                            width="60"
                                        >
                                            <template #trigger>
                                                <span
                                                    class="inline-flex rounded-md"
                                                >
                                                    <button
                                                        type="button"
                                                        class="inline-flex items-center px-1 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-transparent hover:text-gray-700 focus:outline-none transition"
                                                    >
                                                        <svg
                                                            class="ml-2 -mr-0.5 h-4 w-4"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20"
                                                            fill="currentColor"
                                                        >
                                                            <path
                                                                fill-rule="evenodd"
                                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                clip-rule="evenodd"
                                                            />
                                                        </svg>
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="h-6 w-6"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                            stroke-width="2"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                                                            />
                                                        </svg>
                                                    </button>
                                                    <div>
                                                        <h1
                                                            class="block px-1 py-0 my-2 text-xs text-gray-100 bg-red-600 rounded-full"
                                                            id="notifications-count"
                                                        >
                                                            {{
                                                                notificationsCount
                                                            }}
                                                        </h1>
                                                    </div>
                                                </span>
                                            </template>

                                            <template #content>
                                                <!-- Account Management -->
                                                <div
                                                    class="flex justify-between"
                                                >
                                                    <div
                                                        class="block px-4 py-2 text-xs text-gray-400"
                                                    >
                                                        Notifications
                                                    </div>
                                                </div>
                                                <div
                                                    class="border-t border-gray-200"
                                                />

                                                <JetDropdownLink
                                                    class="new_message"
                                                    v-for="(
                                                        item, index
                                                    ) in items"
                                                    :key="index"
                                                    v-show="showNewMessage"
                                                >
                                                    <!-- add show class on dropdown open js -->

                                                    <div class="flex py-1">
                                                        <div
                                                            class="my-auto rtl:ml-2 ltr:mr-2"
                                                        >
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 24 24"
                                                                fill="currentColor"
                                                                class="w-10 h-10"
                                                            >
                                                                <path
                                                                    fill-rule="evenodd"
                                                                    d="M4.5 3.75a3 3 0 00-3 3v10.5a3 3 0 003 3h15a3 3 0 003-3V6.75a3 3 0 00-3-3h-15zm4.125 3a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5zm-3.873 8.703a4.126 4.126 0 017.746 0 .75.75 0 01-.351.92 7.47 7.47 0 01-3.522.877 7.47 7.47 0 01-3.522-.877.75.75 0 01-.351-.92zM15 8.25a.75.75 0 000 1.5h3.75a.75.75 0 000-1.5H15zM14.25 12a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H15a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3.75a.75.75 0 000-1.5H15z"
                                                                    clip-rule="evenodd"
                                                                />
                                                            </svg>
                                                        </div>
                                                        <div
                                                            class="flex flex-col justify-center"
                                                        >
                                                            <Link
                                                                :href="
                                                                    patientDetails
                                                                "
                                                            >
                                                                <h4
                                                                    class="notification label mb-1"
                                                                >
                                                                    {{
                                                                        item.message
                                                                    }}
                                                                    :
                                                                    {{
                                                                        item.patient
                                                                    }}
                                                                </h4>
                                                                <div
                                                                    class="notification-subtext mb-0 leading-tight text-size-xs text-slate-400"
                                                                >
                                                                    <i
                                                                        class="mr-1 fa fa-clock"
                                                                        >{{
                                                                            item.created_at
                                                                        }}</i
                                                                    >
                                                                </div>
                                                            </Link>
                                                        </div>
                                                    </div>
                                                </JetDropdownLink>
                                                <JetDropdownLink
                                                    v-for="(
                                                        item, index
                                                    ) in notifications"
                                                    :key="index"
                                                >
                                                    <!-- add show class on dropdown open js -->

                                                    <div class="flex py-1">
                                                        <div
                                                            class="my-auto rtl:ml-2 ltr:mr-2"
                                                        >
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 24 24"
                                                                fill="currentColor"
                                                                class="w-10 h-10"
                                                            >
                                                                <path
                                                                    fill-rule="evenodd"
                                                                    d="M4.5 3.75a3 3 0 00-3 3v10.5a3 3 0 003 3h15a3 3 0 003-3V6.75a3 3 0 00-3-3h-15zm4.125 3a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5zm-3.873 8.703a4.126 4.126 0 017.746 0 .75.75 0 01-.351.92 7.47 7.47 0 01-3.522.877 7.47 7.47 0 01-3.522-.877.75.75 0 01-.351-.92zM15 8.25a.75.75 0 000 1.5h3.75a.75.75 0 000-1.5H15zM14.25 12a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H15a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3.75a.75.75 0 000-1.5H15z"
                                                                    clip-rule="evenodd"
                                                                />
                                                            </svg>
                                                        </div>
                                                        <div
                                                            class="flex flex-col justify-center"
                                                        >
                                                            <Link
                                                                :href="
                                                                    route(
                                                                        'doctor.patient_details',
                                                                        {
                                                                            id: item.patient_id,
                                                                        }
                                                                    )
                                                                "
                                                            >
                                                                <h4
                                                                    class="notification label mb-1"
                                                                >
                                                                    {{
                                                                        item.message
                                                                    }}
                                                                </h4>
                                                                <div
                                                                    class="notification-subtext mb-0 leading-tight text-size-xs text-slate-400"
                                                                >
                                                                    <i
                                                                        class="mr-1 fa fa-clock"
                                                                        >{{
                                                                            item.created_at
                                                                        }}</i
                                                                    >
                                                                </div>
                                                            </Link>
                                                        </div>
                                                    </div>
                                                </JetDropdownLink>

                                                <div
                                                    class="border-t border-gray-100"
                                                />

                                                <div
                                                    class="border-t border-gray-100"
                                                />
                                            </template>
                                        </JetDropdown>
                                    </div>
                                </div>
                            </li>
                            <!-- //////////////////////////////////////////////////////////////////////////// -->

                            <li class="flex items-center">
                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    class="block rtl:mr-8 ltr:ml-8 px-4 py-2 font-semibold transition-all ease-nav-brand text-size-sm text-slate-500"
                                    as="button"
                                >
                                    <i class="fa fa-user sm:mr-1"></i>
                                    <span class="hidden sm:inline"
                                        >Sign Out</span
                                    >
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- end Navbar -->

            <slot />
        </main>
    </body>
</template>
