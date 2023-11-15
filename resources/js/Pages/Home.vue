<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import { Carousel, Navigation, Slide } from "vue3-carousel";

import "vue3-carousel/dist/carousel.css";

import { ref } from "vue";
import { router, Head, Link, usePage } from "@inertiajs/vue3";
import pkg from "lodash";
const { throttle } = pkg;

const search = ref("");
const user = usePage().props.auth.user;

const searchAvailableTasks = throttle(function () {
    if (!user) {
        router.get(route("login"));
        return;
    }

    router.get(
        route("home"),
        { search: search.value },
        {
            preserveState: true,
        }
    );
}, 500);

const breakpoints = {
    320: {
        itemsToShow: 1,
    },
    768: {
        itemsToShow: 2.5,
    },
    1024: {
        itemsToShow: 3,
    },
};

defineProps({
    availableTasks: Array,
    quereyParam: String,
    brands: Array,
});
</script>

<template>
    <Head title="Home" />

    <AppLayout>
        <div class="flex flex-col min-h-[62vh] items-center">
            <div class="w-full mb-8 md:w-3/5">
                <div class="mt-10">
                    <h1
                        class="text-4xl font-extrabold text-center page-heading text-spaced"
                    >
                        REFERON
                    </h1>

                    <div class="relative">
                        <TextInput
                            type="text"
                            class="relative block w-full mt-1 text-lg text-center"
                            placeholder="Search for brands or stores"
                            v-model="search"
                            @keyup.enter="searchAvailableTasks"
                        />

                        <button
                            class="absolute inset-y-0 my-auto right-4"
                            v-if="search"
                            @click="searchAvailableTasks"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                class="w-5 h-5"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                    </div>
                </div>

                <div
                    class="px-3 py-2 mx-4 mt-2 bg-white divide-y-2 rounded shadow-lg"
                    v-if="availableTasks.length"
                >
                    <Link
                        v-for="task in availableTasks"
                        :key="task.id"
                        :href="route('task.show', task.id)"
                    >
                        <div class="py-3 pl-2 rounded hover:bg-gray-200">
                            {{ task.brand }}
                        </div>
                    </Link>
                </div>

                <div v-if="!availableTasks.length && user && quereyParam">
                    <div class="flex flex-col items-center mt-12">
                        <h1 class="mb-2 text-[22px] text-center">
                            Currently, there is no code for
                            <span class="font-bold"> {{ quereyParam }}.</span>
                        </h1>

                        <h2 class="mt-3 mb-4">Check back here regularly, or</h2>

                        <Link :href="route('task.create')">
                            <button class="text-lg btn btn-primary">
                                Submit details for a new brand or store
                            </button>
                        </Link>
                    </div>
                </div>
            </div>

            <div class="w-full mt-auto">
                <Carousel :breakpoints="breakpoints" :autoplay="5000">
                    <Slide
                        v-for="(brand, index) in brands"
                        :key="index"
                        class="md:!w-[26%] md:!mx-auto"
                    >
                        <img :src="brand.logo_url" alt="not found" />
                    </Slide>

                    <template #addons>
                        <Navigation />
                    </template>
                </Carousel>
            </div>
        </div>
    </AppLayout>
</template>
