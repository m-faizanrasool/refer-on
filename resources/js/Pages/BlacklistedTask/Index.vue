<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { TailwindPagination } from "laravel-vue-pagination";
import { ref, watch } from "vue";
import pkg from "lodash";

const { throttle } = pkg;

const search = ref("");

const viewPage = (page) => {
    router.visit(route("blacklisted-tasks.index", { page }));
};

watch(
    search,
    throttle(function (value) {
        router.get(
            route("blacklisted-tasks.index"),
            { search: value },
            {
                preserveState: true,
                replace: true,
            }
        );
    }, 500)
);

//define props
defineProps({
    blacklistedTasks: Object,
});
</script>

<template>
    <AppLayout>
        <Head title="Blacklisted-Tasks"></Head>

        <h1 class="page-heading">Blacklisted Tasks</h1>

        <input
            ref="searchField"
            v-model="search"
            @keydown.window.prevent.slash="ref.searchField.focus()"
            placeholder="Search..."
            type="search"
            class="block w-full px-4 py-3 mb-6 text-gray-700 rounded-lg focus:outline-none focus:shadow"
        />

        <div>
            <div class="flex justify-end my-4">
                <Link
                    class="btn btn-primary"
                    :href="route('blacklisted-tasks.create')"
                >
                    Add new
                </Link>
            </div>

            <div
                class="flex flex-col justify-between w-full px-6 py-4 mb-4 bg-white border rounded-md shadow-lg lg:items-center lg:flex-row lg:px-3"
                v-for="task in blacklistedTasks.data"
            >
                <div
                    class="flex flex-col gap-2 mb-4 lg:gap-12 lg:flex-row lg:mb-0"
                >
                    <div class="lg:min-w-[140px]">
                        <div class="font-bold">Country</div>
                        <div class="text-xl truncate">
                            {{ task.country.name }}
                        </div>
                    </div>

                    <div class="lg:w-[140px]">
                        <div class="font-bold">Brand</div>
                        <div class="text-xl truncate">
                            {{ task.brand_name }}
                        </div>
                    </div>

                    <div>
                        <div class="font-bold">Code</div>
                        <div class="text-xl">{{ task.code }}</div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <Link
                        class="btn btn-success"
                        :href="route('blacklisted-tasks.edit', task.id)"
                    >
                        Edit
                    </Link>

                    <button
                        @click="
                            $inertia.delete(
                                route('blacklisted-tasks.destroy', task.id)
                            )
                        "
                        class="text-red-500"
                        onclick="return confirm('Are you sure?')"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <TailwindPagination
                :data="blacklistedTasks"
                @pagination-change-page="viewPage"
            />
        </div>
    </AppLayout>
</template>
