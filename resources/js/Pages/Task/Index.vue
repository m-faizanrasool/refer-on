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

        <div class="flex items-center justify-between mb-8">
            <h1 class="page-heading">My Tasks</h1>

            <Link
                class="block px-12 btn btn-primary"
                as="button"
                :href="route('profile.detail')"
            >
                Task Details
            </Link>
        </div>

        <div>
            <div
                class="flex flex-col justify-between w-full px-6 py-4 mb-4 bg-white border rounded-md shadow-md lg:items-center lg:flex-row lg:px-3"
                v-for="task in tasks?.data"
            >
                <div
                    class="flex flex-col gap-3 mb-4 lg:gap-12 lg:flex-row lg:mb-0"
                >
                    <div class="max-w-[120px] min-w-[100px]">
                        <div class="font-bold">Submitter</div>
                        <div class="text-lg truncate">
                            {{
                                task.submitter_id === $page.props.auth.user.id
                                    ? "You"
                                    : task.executor_name
                            }}
                        </div>
                    </div>

                    <div class="max-w-[250px] min-w-[100px]">
                        <div class="font-bold">Brand</div>
                        <div class="text-lg truncate">
                            {{ task.brand_name }}
                        </div>
                    </div>

                    <div class="max-w-[120px] min-w-[100px]">
                        <div class="font-bold">Code</div>
                        <div class="text-lg truncate">{{ task.code }}</div>
                    </div>

                    <div class="max-w-[220px] min-w-[220px]">
                        <div class="font-bold">Status</div>
                        <div class="text-lg truncate">
                            {{ task.status }}
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex gap-4">
                        <Link
                            class="btn btn-primary"
                            :href="route('task.fulfill', task.id)"
                            v-if="
                                task.status === 'PENDING_VERIFICATION' &&
                                task.submitter_id !==
                                    $page.props.auth.user.id &&
                                !task.childs.length
                            "
                        >
                            Fulfill
                        </Link>

                        <button
                            @click="
                                $inertia.delete(route('task.destroy', task.id))
                            "
                            class="text-red-500"
                            v-if="
                                task.submitter_id ===
                                    $page.props.auth.user.id &&
                                task.status === 'AVAILABLE'
                            "
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
