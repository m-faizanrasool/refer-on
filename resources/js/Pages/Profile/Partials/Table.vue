<script setup>
import TextInput from "@/Components/TextInput.vue";

import { computed, ref } from "vue";
import { router } from "@inertiajs/vue3";

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
const sortBy = ref("brand_name");
const sortTasksDesc = ref(false);

const select = ref();

const tableHeaders = {
    country_name: "Country",
    formatted_created_at: "Date",
    brand_name: "Brand",
    submitter_name: "Submitter",
    executor_name: "Executor",
    submitter_credits: "Earnings",
    status: "Status",
    submitter_demerit_points: "Dermit Points",
};

const filterTasks = (searchValue, userId) => {
    return props.tasks.filter((task) => {
        const searchLower = searchValue.toLowerCase();

        const fieldsToSearch = [
            task.country_name,
            task.formatted_created_at,
            task.brand_name,
            task.submitter.name,
            task.executor.name,
            task.status,
        ];

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

const statuses = [
    { name: "VERIFED", value: "VERIFED", class: "text-green-400" },
    { name: "INVALID", value: "INVALID", class: "text-red-400" },
    { name: "DISPUTED", value: "DISPUTED", class: "text-yellow-400" },
    {
        name: "PENDING VERIFICATION",
        value: "PENDING_VERIFICATION",
        class: "text-yellow-400",
    },
];

const updateTaskStatus = (value, curr, taskID) => {
    if (value == curr.toLowerCase()) {
        return;
    }

    if (confirm(`Are you sure you want to update the status to ${value}`)) {
        router.patch(route("task.updateStatus"), {
            task_id: taskID,
            status: value,
        });
    } else {
        select.value.value = curr;
    }
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
            <div>{{ task.executor_name }}</div>
            <div>{{ task.executor_credits }}</div>
            <div class="!text-[68%] !min-w-[200px]">
                <select
                    class="w-full p-0 text-[13px] bg-transparent border-none focus:ring-0"
                    @change="
                        updateTaskStatus(
                            $event.target.value,
                            task.status,
                            task.id
                        )
                    "
                    ref="select"
                >
                    <option
                        v-for="status in statuses"
                        :value="status.value"
                        :class="status.class"
                        :selected="
                            status.value.toLowerCase() ==
                            task.status.toLowerCase()
                        "
                        :key="status.value"
                    >
                        {{ status.name }}
                    </option>
                </select>
            </div>
            <div>{{ task.submitter_demerit_points }}</div>
        </div>
    </div>
</template>
