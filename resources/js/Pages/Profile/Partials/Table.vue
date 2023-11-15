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
    authId: {
        type: Number,
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

let tableHeaders;

if (props.taskType === "submitter") {
    tableHeaders = {
        country_name: "Country",
        formatted_created_at: "Date",
        brand_name: "Brand",
        submitter_name: "Submitter",
        code: "Code",
        executor_name: "Executor",
        submitter_credits: "Earnings",
        status: "Status",
        demerit_points: "Demerit Points",
    };
} else {
    tableHeaders = {
        country_name: "Country",
        formatted_created_at: "Date",
        brand_name: "Brand",
        submitter_name: "Submitter",
        code: "Code",
        executor_name: "Executor",
        executor_credits: "Earnings",
        status: "Status",
        demerit_points: "Demerit Points",
    };
}

const filterTasks = (searchValue, userId) => {
    return props.tasks.filter((task) => {
        const searchLower = searchValue.toLowerCase();

        const fieldsToSearch = [
            task.country_name,
            task.brand_name,
            task.submitter_name,
            task.code,
            task.submitter_name,
        ];

        if (task?.executor) {
            fieldsToSearch.push(task.executor_name);
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

const sortTasks = (tasks) => {
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
    let filteredTasks = filterTasks(search.value, props.authId, props.taskType);
    return sortTasks(filteredTasks);
});

const statusesExecutor = [
    {
        label: "Pending execution",
        value: "AVAILABLE",
        class: "text-green-500",
    },
    {
        label: "Pending your verification",
        value: "PENDING_VERIFICATION",
        class: "text-blue-600",
    },
    { label: "Verified by you", value: "VERIFIED", class: "text-blue-900" },
    { label: "Disputed by you", value: "DISPUTED", class: "text-red-400" },
    {
        label: "Invalid code",
        value: "INVALID",
        class: "text-red-400",
    },
    {
        label: "Blacklisted Code",
        value: "BLACKLISTED",
        class: "text-red-400",
    },
    {
        label: "Duplicate Code",
        value: "DUPLICATE_CODE",
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
        class: "text-green-700",
    },
    {
        label: "Disputed by Submitter",
        value: "DISPUTED",
        class: "text-red-400",
    },
    {
        label: "Invalid code",
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
    }

    return filteredStatus;
};

const updateTaskStatus = (status, task_id, canDispute) => {
    const message =
        "Brands may need some time to process the referral codes. Please wait 15 days before checking with them, and then submit a dispute if necessary.";

    if (status === "DISPUTED" && !canDispute) {
        Toastify({
            text: message,
            className: "toastify-error",
            duration: 10000,
            close: true,
            stopOnFocus: true,
        }).showToast();
        return;
    }

    router.patch(
        route("task.updateStatus"),
        {
            task_id,
            status,
        },
        {
            preserveState: true,
        }
    );
};
</script>

<template>
    <div class="relative">
        <div class="lg:absolute right-0 flex justify-end -top-[57px]">
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
                    :class="
                        key === 'status'
                            ? '!min-w-[180px]'
                            : key === 'formatted_created_at'
                            ? '!min-w-[85px]'
                            : ''
                    "
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
                <div class="truncate">{{ task.country_name }}</div>
                <div class="!min-w-[85px]">{{ task.formatted_created_at }}</div>
                <div class="truncate">{{ task.brand.name }}</div>
                <div class="truncate">{{ task.submitter.username }}</div>
                <div class="truncate">{{ task.code }}</div>
                <div class="truncate">{{ task?.executor?.username ?? "" }}</div>
                <div class="truncate">
                    ${{
                        taskType == "submitter"
                            ? task.submitter_credits
                            : task?.executor_credits
                    }}
                </div>

                <div class="!min-w-[180px] truncate">
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
                        <template v-if="task.status !== 'PENDING_VERIFICATION'">
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

                <div>{{ task.demerit_points }}</div>
            </div>
        </div>
    </div>
</template>
