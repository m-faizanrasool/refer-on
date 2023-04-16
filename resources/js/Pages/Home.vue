<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import Carousel from "@/Components/Carousal.vue";

import { ref, watch } from "vue";
import { router, Head, Link } from "@inertiajs/vue3";
import { throttle } from "lodash";

const search = ref("");

watch(
    search,
    throttle(function (value) {
        router.get(
            route("home"),
            { search: value },
            {
                preserveState: true,
            }
        );
    }, 500)
);

let logos = [
    { src: "images/logos/adidas.png", alt: "adidas" },
    { src: "images/logos/amazon.png", alt: "amazon" },
    { src: "images/logos/ebay.png", alt: "ebay" },
    { src: "images/logos/nike.png", alt: "nike" },
    { src: "images/logos/shopee.png", alt: "shopee" },
];

defineProps({
    availableTasks: Array,
    quereyParam: String,
});
</script>

<template>
    <Head title="Home" />

    <AppLayout>
        <div class="mx-auto md:w-4/6">
            <div class="mt-10">
                <h1 class="font-bold text-center page-heading">REFERON</h1>

                <TextInput
                    type="text"
                    class="block w-full mt-1"
                    :placeholder="
                        !$page.props.auth.user
                            ? 'Please login first to search for brands or stores with tasks'
                            : 'Search for brands or stores with tasks'
                    "
                    v-model="search"
                    :disabled="!$page.props.auth.user"
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

            <div v-if="!availableTasks.length && (search || quereyParam)">
                <div class="flex flex-col items-center mt-10">
                    <h1 class="mb-2 text-2xl font-bold text-center">
                        Currently, there is no task for
                        {{ search || quereyParam }}
                    </h1>

                    <h2 class="mb-6">Check back here regularly, or</h2>

                    <Link :href="route('task.create')">
                        <button class="btn btn-primary">
                            Submit detail for new brand or store
                        </button>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
