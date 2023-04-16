<script setup>
import { throttle } from "lodash";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";

import { TailwindPagination } from "laravel-vue-pagination";

import { ref, watchEffect, watch } from "vue";

const search = ref("");

const viewPage = (page) => {
    router.visit(route("user.index", { page }));
};

watch(
    search,
    throttle(function (value) {
        router.get(
            route("user.index"),
            { search: value },
            {
                preserveState: true,
                replace: true,
            }
        );
    }, 500)
);

defineProps({
    users: Object,
});
</script>

<template>
    <AppLayout>
        <Head title="Edit" />

        <h1 class="page-heading">Edit</h1>

        <input
            ref="searchField"
            v-model="search"
            @keydown.window.prevent.slash="ref.searchField.focus()"
            placeholder="Search..."
            type="search"
            class="block w-full px-4 py-3 mb-6 text-gray-700 rounded-lg focus:outline-none focus:shadow"
        />

        <div>
            <div
                class="flex flex-col justify-between w-full px-6 py-4 mb-4 bg-white border rounded-md shadow-md lg:items-center lg:flex-row lg:px-3"
                v-for="user in users.data"
            >
                <div
                    class="flex flex-col gap-3 mb-4 lg:gap-12 lg:flex-row lg:mb-0"
                >
                    <div class="max-w-[120px]">
                        <div class="font-bold">Name</div>
                        <div class="text-[20px] truncate">{{ user.name }}</div>
                    </div>

                    <div class="max-w-[250px]">
                        <div class="font-bold">Phone</div>
                        <div class="text-[20px] truncate">{{ user.phone }}</div>
                    </div>

                    <div class="max-w-[2500px]">
                        <div class="font-bold">Email</div>
                        <div class="text-[20px] truncate">
                            {{ user.email }}
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex gap-4">
                        <Link
                            class="btn btn-success"
                            :href="route('profile.detail', user.id)"
                        >
                            Detail
                        </Link>

                        <button
                            @click="
                                $inertia.delete(route('user.destroy', user.id))
                            "
                            class="text-red-500"
                            onclick="return confirm('Are you sure?')"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <TailwindPagination
                :data="users"
                @pagination-change-page="viewPage"
            />
        </div>
    </AppLayout>
</template>
