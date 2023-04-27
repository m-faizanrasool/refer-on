<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { TailwindPagination } from "laravel-vue-pagination";

const viewPage = (page) => {
    router.visit(route("task.index", { page }));
};

defineProps({
    tasks: Object,
});
</script>

<template>
    <AppLayout>
        <Head title="My Task" />

        <h1 class="page-heading">My Tasks</h1>

        <div>
            <div
                class="flex flex-col justify-between w-full px-6 py-4 mb-4 bg-white border rounded-md shadow-md lg:items-center lg:flex-row lg:px-3"
                v-for="task in tasks?.data"
            >
                <div
                    class="flex flex-col gap-3 mb-4 lg:gap-12 lg:flex-row lg:mb-0"
                >
                    <div class="max-w-[120px]">
                        <div class="font-bold">Code</div>
                        <div class="text-[20px] truncate">{{ task.key }}</div>
                    </div>

                    <div class="max-w-[250px]">
                        <div class="font-bold">Brand</div>
                        <div class="text-[20px] truncate">
                            {{ task.brand.name }}
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex gap-4">
                        <Link
                            class="btn btn-success"
                            :href="route('task.edit', task.id)"
                        >
                            Edit
                        </Link>

                        <button
                            @click="
                                $inertia.delete(route('task.destroy', task.id))
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
                :data="tasks"
                @pagination-change-page="viewPage"
            />
        </div>
    </AppLayout>
</template>
