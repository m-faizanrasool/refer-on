<script setup>
import TextInput from "@/Components/TextInput.vue";
import CustomSelect from "@/Pages/Profile/Partials/Select.vue";

import { computed, ref } from "vue";
import { router } from "@inertiajs/vue3";

import Toastify from "toastify-js";

const props = defineProps({
    tasks: {
        type: Object,
        required: true,
    },
    auth: {
        type: Object,
        required: true,
    },
    taskType: {
        type: String,
        required: true,
    },
});

const search = ref("");
const sortBy = ref("formatted_created_at");
const sortTasksDesc = ref(true);

const tableHeaders = {
    country_name: "Country",
    formatted_created_at: "Date",
    brand_name: "Brand",
    submitter_name: "Submitter",
    key: "task",
    executor_name: "Executor",
    submitter_credits: "Earnings",
    status: "Status",
    submitter_demerit_points: "DEMERIT POINTS",
};

const filterTasks = (searchValue, userId) => {
    return props.tasks.filter((task) => {
        const searchLower = searchValue.toLowerCase();

        const fieldsToSearch = [
            task.country_name,
            task.formatted_created_at,
            task.brand_name,
            task.submitter.username,
            task.key,
            task.status,
        ];

        if (task.executor) {
            fieldsToSearch.push(task.executor.username);
        }

        let taskUserId;

        if (props.taskType === "submitter") {
            taskUserId = task.submitter_id;
        }

        if (props.taskType === "executor") {
            taskUserId = task.executor_id;
        }

        return (
            taskUserId === userId &&
            fieldsToSearch.some((field) =>
                field.toLowerCase().includes(searchLower)
            )
        );
    });
};

const sortTasks = (tasks, type) => {
    if (sortBy.value !== "") {
        tasks.sort((a, b) => {
            const sortOrder = sortTasksDesc.value ? -1 : 1;
            const valueA = a[sortBy.value];
            const valueB = b[sortBy.value];

            if (typeof valueA === "number" && typeof valueB === "number") {
                return sortOrder * (valueA - valueB);
            } else {
                const strA = valueA.toString();
                const strB = valueB.toString();
                return sortOrder * strA.localeCompare(strB);
            }
        });
    }

    return tasks;
};

const Tasks = computed(() => {
    let filteredTasks = filterTasks(
        search.value,
        props.auth.user.id,
        props.taskType
    );
    return sortTasks(filteredTasks);
});

const statusesExecutor = [
    {
        label: "Pending execution",
        value: "AVAILABLE",
        class: "text-green-400",
    },
    {
        label: "Pending your verification",
        value: "PENDING_VERIFICATION",
        class: "text-blue-600",
    },
    { label: "Verified by you", value: "VERIFIED", class: "text-green-600" },
    { label: "Disputed by you", value: "DISPUTED", class: "text-green-600" },
    {
        label: "Executor user claimed your task is invalid",
        value: "INVALID",
        class: "text-red-400",
    },
    {
        label: "You tried to create a blacklisted task",
        value: "BLACKLISTED",
        class: "text-red-400",
    },
];

const statusesSubmitter = [
    {
        label: "Hmm error",
        value: "AVAILABLE",
        class: "text-green-400",
    },
    {
        label: "Pending Submitter verification",
        value: "PENDING_VERIFICATION",
        class: "text-blue-600",
    },
    {
        label: "Verified by Submitter",
        value: "VERIFIED",
        class: "text-green-400",
    },
    {
        label: "Disputed by Submitter",
        value: "DISPUTED",
        class: "text-green-400",
    },
    {
        label: "You claimed task is invalid",
        value: "INVALID",
        class: "text-red-400",
    },
];

const filterStatus = (status) => {
    let filteredStatus = [];

    if (status === "PENDING_VERIFICATION") {
        filteredStatus = statusesExecutor.filter(
            (option) =>
                option.value === "VERIFIED" || option.value === "DISPUTED"
        );
    } else if (status === "VERIFIED") {
        filteredStatus = statusesExecutor.filter(
            (option) => option.value === "DISPUTED"
        );
    }

    return filteredStatus;
};

const updateTaskStatus = (status, task_id, canDispute) => {
    if (status === "DISPUTED" && !canDispute) {
        Toastify({
            text: "Task must be open for at least 15 days before it can be disputed.",
            className: "toastify-error",
            duration: 3000,
            close: true,
            stopOnFocus: true,
        }).showToast();
        return;
    }

    router.patch(route("task.updateStatus"), {
        task_id,
        status,
    });
};
</script>

<template>
    <div class="flex justify-end">
        <TextInput
            type="text"
            placeholder="Search"
            v-model="search"
            class="mb-4"
        />
    </div>

    <div class="overflow-x-auto">
        <div class="flex table-head">
            <div
                v-for="(name, key) in tableHeaders"
                @click="
                    sortBy = key;
                    sortTasksDesc = !sortTasksDesc;
                "
                class="flex items-center gap-1 cursor-pointer"
                :class="key === 'status' ? '!min-w-[200px]' : ''"
            >
                <span v-text="name" class="mt-1"></span>

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    class="w-4 h-4"
                    v-if="sortBy === key"
                    :class="
                        sortTasksDesc
                            ? 'transform rotate-180'
                            : 'transform rotate-0'
                    "
                >
                    <path
                        fill="currentColor"
                        d="M18.2 13.3L12 7l-6.2 6.3c-.2.2-.3.5-.3.7s.1.5.3.7c.2.2.4.3.7.3h11c.3 0 .5-.1.7-.3c.2-.2.3-.5.3-.7s-.1-.5-.3-.7z"
                    />
                </svg>
            </div>
        </div>

        <div class="flex table-head" v-for="task in Tasks" :key="task.id">
            <div>{{ task.country_name }}</div>
            <div>{{ task.formatted_created_at }}</div>
            <div>{{ task.brand_name }}</div>
            <div>{{ task.submitter_name }}</div>
            <div>{{ task.key }}</div>
            <div>{{ task.executor_name }}</div>
            <div>
                ${{
                    taskType == "submitter"
                        ? task.submitter_credits
                        : task.executor_credits
                }}
            </div>

            <div class="!min-w-[200px]">
                <div v-if="taskType === 'executor'">
                    <span
                        :class="
                            statusesSubmitter.find(
                                (status) => status.value === task.status
                            ).class
                        "
                        >{{
                            statusesSubmitter.find(
                                (status) => status.value === task.status
                            ).label
                        }}</span
                    >
                </div>

                <div v-if="taskType === 'submitter'">
                    <template
                        v-if="
                            task.status === 'AVAILABLE' ||
                            task.status === 'DISPUTED' ||
                            task.status === 'INVALID' ||
                            task.status === 'BLACKLISTED'
                        "
                    >
                        <span
                            :class="
                                statusesExecutor.find(
                                    (status) => status.value === task.status
                                ).class
                            "
                            >{{
                                statusesExecutor.find(
                                    (status) => status.value === task.status
                                ).label
                            }}</span
                        >
                    </template>

                    <template v-else>
                        <custom-select
                            :options="filterStatus(task.status)"
                            :selected-option="
                                statusesExecutor.filter(
                                    (option) => option.value === task.status
                                )
                            "
                            @update:value="
                                (newValue) =>
                                    updateTaskStatus(
                                        newValue,
                                        task.id,
                                        task.can_dispute
                                    )
                            "
                        />
                    </template>
                </div>
            </div>

            <div>{{ task.submitter_demerit_points }}</div>
        </div>
    </div>
</template>
