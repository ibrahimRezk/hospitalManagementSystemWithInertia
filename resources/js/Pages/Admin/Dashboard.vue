<script setup>

import Layout from '@/Layouts/Authenticated.vue'



</script>

<template>
<Layout>

   <div>
          <!-- cards -->
      <div class="w-full px-6 py-6 mx-auto">
        <!-- row 1 -->
        <div class="flex flex-wrap -mx-3">
          <!-- card1 -->
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row justify-between -mx-3">
                  <div class="flex-none max-w-full px-3">
                    <div>
                      <p class="mb-0 font-sans font-semibold leading-normal text-size-sm">  Sections Total Count  </p>
                      <h5 class="mb-0 font-bold">
                        {{  }}
                        <span class="leading-normal text-size-sm font-weight-bolder text-lime-500">10</span>
                      </h5>
                    </div>
                  </div>
                  <div class="px-3 text-right">
                    <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-fuchsia">
                      <i class="ni ni-money-coins text-size-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
                    <!-- card2 -->

          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row justify-between -mx-3">
                  <div class="flex-none max-w-full px-3">
                    <div>
                      <p class="mb-0 font-sans font-semibold leading-normal text-size-sm">  Doctors Total Count  </p>
                      <h5 class="mb-0 font-bold">
                        {{  }}
                        <span class="leading-normal text-size-sm font-weight-bolder text-lime-500">10</span>
                      </h5>
                    </div>
                  </div>
                  <div class="px-3 text-right">
                    <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-fuchsia">
                      <i class="ni ni-money-coins text-size-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- card3 -->
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row justify-between -mx-3">
                  <div class="flex-none max-w-full px-3">
                    <div>
                      <p class="mb-0 font-sans font-semibold leading-normal text-size-sm"> Patients Total Count</p>
                      <h5 class="mb-0 font-bold">
                        {{  }}
                        <span class="leading-normal text-size-sm font-weight-bolder text-lime-500">200</span>
                      </h5>
                    </div>
                  </div>
                  <div class="px-3 text-right">
                    <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-fuchsia">
                      <i class="ni ni-world text-size-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- card4 -->
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
              <div class="flex-auto  p-4">
                <div class="flex flex-row justify-between -mx-3">
                  <div class="flex-none max-w-full px-3">
                    <div>
                      <p class="mb-0 font-sans font-semibold leading-normal text-size-sm">Radiology and Laboratory employees count</p>
                      <h5 class="mb-0 font-bold">
                        {{  }}
                        <span class="leading-normal text-red-600 text-size-sm font-weight-bolder">12</span>
                      </h5>
                    </div>
                  </div>
                  <div class="px-3 text-right ">
                    <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-fuchsia">
                      <i class="ni ni-paper-diploma text-size-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          
        </div>

        <!-- cards row 2 -->
        <div class="flex flex-wrap mt-6 -mx-3">
          <div class="w-full px-3 mb-6 lg:mb-0 lg:w-7/12 lg:flex-none">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-wrap  -mx-3">
                  <div class="max-w-full  px-3 lg:w-1/2 lg:flex-none">
                    <div class="flex flex-col h-full">
                      <p class="pt-2 mb-1 font-semibold">Total income and expense</p>
                      <h5 class="font-bold">Total income</h5>
                      <p class="mb-12">100000000</p>
                      <h5 class="font-bold">Total expense</h5>
                      <p class="mb-12">100000</p>
                      <a class="mt-auto mb-0 font-semibold leading-normal text-size-sm group text-slate-500" href="javascript:;">

                        <h5 class="font-bold">benifit</h5>
                      <p class="mb-12">100000</p>
                        Read More
                        <i class="fas fa-arrow-right ease-bounce text-size-sm group-hover:translate-x-1.25 ml-1 leading-normal transition-all duration-200"></i>
                      </a>
                    </div>
                  </div>
                  <div class="max-w-full px-3 mt-12 ml-auto text-center lg:mt-0 lg:w-6/12 lg:flex-none">
                    <div class="h-full bg-gradient-fuchsia rounded-xl">
                      <img src="../../../../public/admin/assets/img/shapes/waves-white.svg" class="absolute top-0 hidden w-1/2 h-full lg:block" alt="waves" />
                      <div class="relative flex items-center justify-center h-full">
                        <img class="relative z-20 w-full pt-6" src="../../../../public/admin/assets/img/illustrations/rocket-white.png" alt="rocket" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="w-full max-w-full px-3 lg:w-5/12 lg:flex-none">
            <div class="border-black/12.5 shadow-soft-xl relative flex h-full min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border p-4">
              <div class="relative h-full overflow-hidden bg-cover rounded-xl" style="background-image: url({{ asset('../../../../public/admin/assets/img/ivancik.jpg') }})">
                <span class="absolute top-0 left-0 w-full h-full bg-center bg-cover bg-gradient-dark-gray opacity-80"></span>
                <div class="relative z-10 flex flex-col flex-auto h-full p-4">
                  <h5 class="pt-2 mb-6 font-bold text-white">income of last month</h5>
                  <p class="text-white">100000</p>
                  <br>
                  <br>
                  <h5 class="pt-2 mb-6 font-bold text-white">expense of last month</h5>
                  <p class="text-white">10000</p>
                  <a class="mt-auto mb-0 font-semibold leading-normal text-white group text-size-sm" href="javascript:;">
                    Read More
                    <i class="fas fa-arrow-right ease-bounce text-size-sm group-hover:translate-x-1.25 ml-1 leading-normal transition-all duration-200"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- cards row 3 -->

 
        <!-- cards row 4 -->

    

      </div>
      <!-- end cards -->
    </div>

</Layout>
</template>
