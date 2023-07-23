<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import Table from "@/Pages/Profile/Partials/Table.vue";
import { Head } from "@inertiajs/vue3";

const calculateTaskStats = (tasks, type) => {
    let filteredTasks;
    if (type === "submitter") {
        filteredTasks = tasks.filter(
            (t) =>
                t.submitter_id === props.authId &&
                t.executor_id !== null &&
                !["INVALID", "DISPUTED"].includes(t.status)
        );
    } else if (type === "executor") {
        filteredTasks = tasks.filter(
            (t) =>
                t.executor_id === props.authId &&
                !["INVALID", "DISPUTED"].includes(t.status)
        );
    } else {
        return { count: 0, earnings: 0 };
    }

    const count = filteredTasks.length;
    const earnings = filteredTasks.reduce((total, t) => {
        if (type === "submitter") {
            return total + t.brand.submitter_credits;
        } else if (type === "executor") {
            return total + t.brand.executor_credits;
        }
    }, 0);

    return { count, earnings };
};

const props = defineProps({
    authId: Number,
    tasks: Object,
    blackDupTasks: Object,
});
</script>

<template>
    <Head title="Account Detail" />

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
                            :value="calculateTaskStats(tasks, 'executor').count"
                            readonly
                        />

                        <TextInput
                            type="text"
                            class="w-32 text-center"
                            :value="`$${
                                calculateTaskStats(tasks, 'executor').earnings
                            }`"
                            readonly
                        />
                    </div>
                </div>

                <Table :tasks="tasks" :auth-id="authId" task-type="executor" />
            </div>

            <div class="mb-8">
                <div
                    class="flex flex-col justify-between mb-4 gap-y-3 md:items-center md:flex-row"
                >
                    <div class="flex items-center gap-2 text-xl font-bold">
                        Your codes that other users fulfilled:

                        <TextInput
                            type="text"
                            class="w-32 text-center"
                            :value="
                                calculateTaskStats(tasks, 'submitter').count
                            "
                            readonly
                        />

                        <TextInput
                            type="text"
                            class="w-32 text-center"
                            :value="`$${
                                calculateTaskStats(tasks, 'submitter').earnings
                            }`"
                            readonly
                        />
                    </div>
                </div>

                <Table :tasks="tasks" :auth-id="authId" task-type="submitter" />
            </div>

            <div>
                <div
                    class="flex flex-col justify-between mb-4 gap-y-3 md:items-center md:flex-row"
                >
                    <div class="flex items-center gap-2 text-xl font-bold">
                        Blacklisted and Duplicated records:

                        <TextInput
                            type="text"
                            class="w-32 text-center"
                            :value="
                                calculateTaskStats(blackDupTasks, 'submitter')
                                    .count
                            "
                            readonly
                        />

                        <TextInput
                            type="text"
                            class="w-32 text-center"
                            value="$0"
                            readonly
                        />
                    </div>
                </div>

                <Table
                    :tasks="blackDupTasks"
                    :auth-id="authId"
                    task-type="submitter"
                />
            </div>
        </div>
    </AppLayout>
</template>
