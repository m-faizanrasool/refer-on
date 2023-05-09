<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import Table from "@/Pages/Profile/Partials/Table.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
    authId: Number,
    tasks: Object,
    fulfilledTasks: Number,
    fulfilledTasksEarnings: Number,
    tasksFulfilledByOthers: Number,
    tasksFulfilledByOthersEarnings: Number,
});
</script>

<template>
    <Head title="Account" />

    <AppLayout>
        <h1 class="page-heading">Account Details</h1>

        <div>
            <div class="mb-8">
                <div
                    class="flex flex-col justify-between mb-4 gap-y-3 md:items-center md:flex-row"
                >
                    <div class="flex items-center gap-2 text-xl font-bold">
                        Codes that you fulfilled:

                        <TextInput
                            type="text"
                            class="w-32 text-center"
                            :value="fulfilledTasks"
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

                <Table :tasks="tasks" :auth-id="authId" task-type="executor" />
            </div>

            <div>
                <div
                    class="flex flex-col justify-between mb-4 gap-y-3 md:items-center md:flex-row"
                >
                    <div class="flex items-center gap-2 text-xl font-bold">
                        Your codes that other users fulfilled:

                        <TextInput
                            type="text"
                            class="w-32 text-center"
                            :value="tasksFulfilledByOthers"
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

                <Table :tasks="tasks" :auth-id="authId" task-type="submitter" />
            </div>
        </div>
    </AppLayout>
</template>
