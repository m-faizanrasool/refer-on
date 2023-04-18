<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";

import { ref, watch } from "vue";
import { router, Head, Link, usePage } from "@inertiajs/vue3";
import pkg from "lodash";
const { throttle } = pkg;

const search = ref("");
const user = usePage().props.auth.user;

// watch(
//     search,
//     throttle(function (value) {
//         if (!user) {
//             router.get(route("login"));
//             return;
//         }
//         router.get(
//             route("home"),
//             { search: value },
//             {
//                 preserveState: true,
//             }
//         );
//     }, 500)
// );

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

defineProps({
    availableTasks: Array,
    quereyParam: String,
});
</script>

<template>
    <Head title="Home" />

    <AppLayout>
        <div class="mx-auto md:w-3/5">
            <div class="mt-10">
                <h1 class="font-bold text-center page-heading">REFERON</h1>

                <TextInput
                    type="search"
                    class="block w-full mt-1 text-center"
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
                        <button class="btn btn-primary">
                            Submit details for a new brand or store
                        </button>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
