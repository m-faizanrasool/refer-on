<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import Table from "@/Pages/Profile/Partials/Table.vue";
import { Head, usePage } from "@inertiajs/vue3";

const props = usePage().props;

defineProps({
    fulfilledTaskCount: Number,
    fulfilledTasksEarnings: Number,
    tasksFulfilledByOthersCount: Number,
    tasksFulfilledByOthersEarnings: Number,
});
</script>

<template>
    <Head title="Account" />

    <AppLayout>
        <h1 class="page-heading">Account Detail</h1>

        <div>
            <div class="mb-8">
                <div
                    class="flex flex-col justify-between mb-4 gap-y-3 md:items-center md:flex-row"
                >
                    <div class="flex items-center gap-2 font-bold">
                        Tasks that you fulfilled:

                        <TextInput
                            type="text"
                            class="w-32 text-center"
                            :value="fulfilledTaskCount"
                            readonly
                        />

                        <TextInput
                            type="text"
                            class="w-32 text-center"
                            :value="`$${fulfilledTasksEarnings}`"
                            readonly
                        />
                    </div>
                </div>

                <Table
                    :tasks="props.tasks"
                    :auth="props.auth"
                    task-type="executor"
                />
            </div>

            <div>
                <div
                    class="flex flex-col justify-between mb-4 gap-y-3 md:items-center md:flex-row"
                >
                    <div class="flex items-center gap-2 font-bold">
                        Your tasks that other fulfilled:

                        <TextInput
                            type="text"
                            class="w-32 text-center"
                            :value="tasksFulfilledByOthersCount"
                            readonly
                        />

                        <TextInput
                            type="text"
                            class="w-32 text-center"
                            :value="`$${tasksFulfilledByOthersEarnings}`"
                            readonly
                        />
                    </div>
                </div>

                <Table
                    :tasks="props.tasks"
                    :auth="props.auth"
                    task-type="submitter"
                />
            </div>
        </div>
    </AppLayout>
</template>