<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

//define props
defineProps({
    blacklistedTask: Object,
});
</script>

<template>
    <AppLayout>
        <Head title="Blacklisted-Tasks"></Head>

        <h1 class="page-heading">Blacklisted Tasks</h1>

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
                v-for="task in blacklistedTask"
            >
                <div
                    class="flex flex-col gap-3 mb-4 lg:gap-12 lg:flex-row lg:mb-0"
                >
                    <div class="lg:w-[130px]">
                        <div class="font-bold">Task</div>
                        <div class="text-xl truncate">{{ task.code }}</div>
                    </div>

                    <div class="lg:w-[130px]">
                        <div class="font-bold">Brand</div>
                        <div class="text-xl truncate">
                            {{ task.brand_key }}
                        </div>
                    </div>

                    <div class="lg:w-[130px]">
                        <div class="font-bold">Country</div>
                        <div class="text-xl truncate">
                            {{ task.country.name }}
                        </div>
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
    </AppLayout>
</template>
