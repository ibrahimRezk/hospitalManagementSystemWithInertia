<script setup>
import { Head, useForm, Link } from "@inertiajs/inertia-vue3";
import { onMounted, ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Layout from "@/Layouts/Authenticated.vue";
import Container from "@/Components/Container.vue";
import Card from "@/Components/Card/Card.vue";
import Table from "@/Components/Table/Table.vue";
import Td from "@/Components/Table/Td.vue";
import Actions from "@/Components/Table/Actions.vue";
import Button from "@/Components/Button.vue";
import Modal from "@/Components/ConfirmationModal.vue";
import DialogModal from "@/Components/DialogModal.vue";
import JetDropdown from "@/components/Dropdown.vue";
import JetDropdownLink from "@/components/DropdownLink.vue";
import InputError from "@/Components/InputError.vue";

import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";
import AddNew from "@/Components/AddNew.vue";
import YesNoLabel from "@/Components/YesNoLabel.vue";
// import Filters from "./Filters.vue";

import useDeleteItem from "@/Composables/useDeleteItem.js";
import useDialogModal from "@/Composables/useDialogModal.js";
import useFilters from "@/Composables/useFilters.js";

const props = defineProps({
  errors: Object,
  title: {
    type: String,
    required: true,
  },

  items: {
    type: Object,
    default: () => ({}),
  },
  total_number: {
    type: String,
    required: false,
  },
  total_underProccessing: {
    type: String,
    required: false,
  },
  total_reviewed: {
    type: String,
    required: false,
  },
  total_completed: {
    type: String,
    required: false,
  },
  headers: {
    type: Array,
    default: () => [],
  },
  // filters: {
  //     type: Object,
  //     default: () => ({}),
  // },
  routeResourceName: {
    type: String,
    required: true,
  },
  can: Object,

  method: String,
});

const { filters, isLoading, isFilled } = useFilters({
  filters: props.filters,
  routeResourceName: props.routeResourceName,
  method: props.method,
});

// if you want to make color changed at once make the function in a new page not a modal
const color = (item) => {
  return item.invoice_status == "completed"
    ? "green"
    : item.invoice_status == "reviewing"
    ? "yellow"
    : "red";
};
</script>
    
    <template>
  <Head :title="title" />

  <Layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ title }}
      </h2>
    </template>
    <Container>
      <!-- <AddNew :show="isFilled">
                        <template #filters>
                            <Filters v-model="filters" :sections="sections" />
                        </template>
                    </AddNew> -->

      <Card class="mt-4">
        <!-- cards -->
        <div class="w-full px-6 py-6 mx-auto">
          <!-- row 1 -->
          <div class="flex flex-wrap -mx-3">
            <!-- card1 -->
            <div
              class="
                w-full
                max-w-full
                px-3
                mb-6
                md:w-1/2 sm:flex-none
                xl:my-1 2xl:w-1/4
              "
            >
              <div
                class="
                  relative
                  flex flex-col
                  min-w-0
                  break-words
                  bg-white
                  shadow-soft-xl
                  rounded-2xl
                  bg-clip-border
                "
              >
                <div class="flex-auto p-4">
                  <div class="flex flex-row justify-between -mx-3">
                    <div class="flex-none max-w-full px-3">
                      <div>
                        <p
                          class="
                            mb-0
                            font-sans font-semibold
                            leading-normal
                            text-size-sm
                          "
                        >
                          total invoices number
                        </p>
                        <h5 class="mb-0 font-bold">
                          {{}}
                          <span
                            class="
                              leading-normal
                              text-size-sm
                              font-weight-bolder
                              text-lime-500
                            "
                            >{{props.total_number}}</span
                          >
                        </h5>
                      </div>
                    </div>
                    <div class="px-3 text-right">
                      <div
                        class="
                          inline-block
                          w-12
                          h-12
                          text-center
                          rounded-lg
                          bg-gradient-fuchsia
                        "
                      >
                        <i
                          class="
                            ni ni-money-coins
                            text-size-lg
                            relative
                            top-3.5
                            text-white
                          "
                        ></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- card2 -->

            <div
              class="
                w-full
                max-w-full
                px-3
                mb-6
                md:w-1/2 sm:flex-none
                xl:my-1 2xl:w-1/4
              "
            >
              <div
                class="
                  relative
                  flex flex-col
                  min-w-0
                  break-words
                  bg-white
                  shadow-soft-xl
                  rounded-2xl
                  bg-clip-border
                "
              >
                <div class="flex-auto p-4">
                  <div class="flex flex-row justify-between -mx-3">
                    <div class="flex-none max-w-full px-3">
                      <div>
                        <p
                          class="
                            mb-0
                            font-sans font-semibold
                            leading-normal
                            text-size-sm
                          "
                        >
                          under proccessing invoices
                        </p>
                        <h5 class="mb-0 font-bold">
                          {{}}
                          <span
                            class="
                              leading-normal
                              text-size-sm
                              font-weight-bolder
                              text-lime-500
                            "
                            >{{props.total_underProccessing}}</span
                          >
                        </h5>
                      </div>
                    </div>
                    <div class="px-3 text-right">
                      <div
                        class="
                          inline-block
                          w-12
                          h-12
                          text-center
                          rounded-lg
                          bg-gradient-fuchsia
                        "
                      >
                        <i
                          class="
                            ni ni-money-coins
                            text-size-lg
                            relative
                            top-3.5
                            text-white
                          "
                        ></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- card3 -->
            <div
              class="
                w-full
                max-w-full
                px-3
                mb-6
                md:w-1/2 sm:flex-none
                xl:my-1 2xl:w-1/4
              "
            >
              <div
                class="
                  relative
                  flex flex-col
                  min-w-0
                  break-words
                  bg-white
                  shadow-soft-xl
                  rounded-2xl
                  bg-clip-border
                "
              >
                <div class="flex-auto p-4">
                  <div class="flex flex-row justify-between -mx-3">
                    <div class="flex-none max-w-full px-3">
                      <div>
                        <p
                          class="
                            mb-0
                            font-sans font-semibold
                            leading-normal
                            text-size-sm
                          "
                        >
                          completed invoices
                        </p>
                        <h5 class="mb-0 font-bold">
                          {{}}
                          <span
                            class="
                              leading-normal
                              text-size-sm
                              font-weight-bolder
                              text-lime-500
                            "
                            >{{props.total_completed}}</span
                          >
                        </h5>
                      </div>
                    </div>
                    <div class="px-3 text-right">
                      <div
                        class="
                          inline-block
                          w-12
                          h-12
                          text-center
                          rounded-lg
                          bg-gradient-fuchsia
                        "
                      >
                        <i
                          class="
                            ni ni-world
                            text-size-lg
                            relative
                            top-3.5
                            text-white
                          "
                        ></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- card4 -->
            <div
              class="
                w-full
                max-w-full
                px-3
                mb-6
                md:w-1/2 sm:flex-none
                xl:my-1 2xl:w-1/4 
              "
            >
              <div
                class="
                  relative
                  flex flex-col
                  min-w-0
                  break-words
                  bg-white
                  shadow-soft-xl
                  rounded-2xl
                  bg-clip-border
                "
              >
                <div class="flex-auto p-4">
                  <div class="flex flex-row justify-between -mx-3">
                    <div class="flex-none max-w-full px-3">
                      <div>
                        <p
                          class="
                            mb-0
                            font-sans font-semibold
                            leading-normal
                            text-size-sm
                          "
                        >
                          review invoices
                        </p>
                        <h5 class="mb-0 font-bold">
                          {{}}
                          <span
                            class="
                              leading-normal
                              text-size-sm
                              font-weight-bolder
                              text-lime-500
                            "
                            >{{props.total_reviewed}}</span
                          >
                        </h5>
                      </div>
                    </div>
                    <div class="px-3 text-right">
                      <div
                        class="
                          inline-block
                          w-12
                          h-12
                          text-center
                          rounded-lg
                          bg-gradient-fuchsia
                        "
                      >
                        <i
                          class="
                            ni ni-world
                            text-size-lg
                            relative
                            top-3.5
                            text-white
                          "
                        ></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </Card>

      <Card class="mt-4" :is-loading="isLoading" no-padding>
        <Table :headers="headers" :items="items">
          <template #section>
            <div
              class="
                p-6
                pb-0
                mb-0
                bg-gray-300
                border-b-0 border-b-solid
                rounded-t-2xl
                border-b-transparent
              "
            >
              <h6>{{ props.title }}</h6>
            </div>
          </template>
          <template v-slot="{ item }">
            <Td>
              <template #image>
                <div class="w-8 h-8 rtl:ml-1 ltr:mr-1">
                  <div
                    v-for="(image, index) in item.patient.images"
                    :key="index"
                    v-html="image.html"
                    class="
                      [&_img]:h-full [&_img]:w-full [&_img]:object-contain
                      shadow-xl
                    "
                  ></div>
                </div>
              </template>
              {{ item.patient.name }}
            </Td>
            <Td>
              <Button color="blue" small>
                {{ item.service?.name??item.group?.name }}
              </Button>
            </Td>

            <Td>
              <Button color="blue" small>
                {{ item.created_at_formatted }}
              </Button>
            </Td>


            <Td>
              <Button small :color="color(item)">
                {{ item.invoice_status }}
              </Button>
            </Td>
          </template>
        </Table>
      </Card>
    </Container>
  </Layout>

 

</template>
    