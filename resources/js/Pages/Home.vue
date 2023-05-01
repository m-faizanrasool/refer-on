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

let logos = [
    { src: "images/logos/adidas.png", alt: "adidas" },
    { src: "images/logos/amazon.png", alt: "amazon" },
    { src: "images/logos/ebay.png", alt: "ebay" },
    { src: "images/logos/nike.png", alt: "nike" },
    { src: "images/logos/shopee.png", alt: "shopee" },
];

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

                    <TextInput
                        type="search"
                        class="block w-full mt-1 text-lg text-center"
                        placeholder="Search for brands or stores"
                        v-model="search"
                        @keyup.enter="searchAvailableTasks"
                    />
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
                        <h1 class="mb-2 text-3xl text-center">
                            Currently, there is no task for
                            <span class="font-bold"> {{ quereyParam }}.</span>
                        </h1>

                        <h2 class="mt-5 mb-4">Check back here regularly, or</h2>

                        <Link :href="route('task.create')">
                            <button class="text-lg btn btn-primary">
                                Submit details for a new brand or store
                            </button>
                        </Link>
                    </div>
                </div>
            </div>

            <div class="w-full mt-auto">
                <Carousel
                    :breakpoints="breakpoints"
                    :autoplay="5000"
                    :wrap-around="true"
                >
                    <Slide v-for="(logo, index) in logos" :key="index">
                        <img :src="logo.src" :alt="logo.alt" />
                    </Slide>

                    <template #addons>
                        <Navigation />
                    </template>
                </Carousel>
            </div>
        </div>
    </AppLayout>
</template>
