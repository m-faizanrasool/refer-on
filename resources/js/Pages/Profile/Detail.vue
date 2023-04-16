<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const props = usePage().props;

const searchOtherTasks = ref("");
const searchMyTasks = ref("");
const sortFulfilledTasksBy = ref("formatted_created_at");
const sortFulfilledTasksDesc = ref(true);

const filterTasks = (searchValue, userId, taskType) => {
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

        if (taskType === "submitter") {
            taskUserId = task.submitter_id;
        }

        if (taskType === "executor") {
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
    if (sortFulfilledTasksBy.value !== "") {
        tasks.sort((a, b) => {
            const sortOrder = sortFulfilledTasksDesc.value ? -1 : 1;
            const valueA = a[sortFulfilledTasksBy.value];
            const valueB = b[sortFulfilledTasksBy.value];

            return sortOrder * valueA.localeCompare(valueB);
        });
    }

    return tasks;
};

const fulfilledTasks = computed(() => {
    let filteredTasks = filterTasks(
        searchOtherTasks.value,
        props.auth.user.id,
        "executor"
    );
    return sortTasks(filteredTasks);
});

const tasksFulfilledByOthers = computed(() => {
    let filteredTasks = filterTasks(
        searchMyTasks.value,
        props.auth.user.id,
        "submitter"
    );
    return sortTasks(filteredTasks);
});

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

                    <div>
                        <TextInput
                            type="text"
                            placeholder="Search"
                            v-model="searchOtherTasks"
                        />
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <div class="flex table-head">
                        <div>Country</div>
                        <div>Date</div>
                        <div>Brand</div>
                        <div>Submitter</div>
                        <div>Executor</div>
                        <div>Earnings</div>
                        <div>Status</div>
                        <div>Dermit Points</div>
                    </div>

                    <div
                        class="flex table-head"
                        v-for="task in fulfilledTasks"
                        :key="task.id"
                    >
                        <div>{{ task.country_name }}</div>
                        <div>{{ task.formatted_created_at }}</div>
                        <div>{{ task.brand_name }}</div>
                        <div>{{ task.submitter.name }}</div>
                        <div>{{ task.executor.name }}</div>
                        <div>{{ task.executor_credits }}</div>
                        <div class="!text-[68%]">{{ task.status }}</div>
                        <div>{{ task.submitter.demerit_points }}</div>
                    </div>
                </div>
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

                    <div>
                        <TextInput
                            type="text"
                            placeholder="Search"
                            v-model="searchMyTasks"
                        />
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <div class="flex table-head">
                        <div>Country</div>
                        <div>Date</div>
                        <div>Brand</div>
                        <div>Submitter</div>
                        <div>Executor</div>
                        <div>Earnings</div>
                        <div>Status</div>
                        <div>Dermit Points</div>
                    </div>

                    <div
                        class="flex table-head"
                        v-for="task in tasksFulfilledByOthers"
                        :key="task.id"
                    >
                        <div>{{ task.country_name }}</div>
                        <div>{{ task.formatted_created_at }}</div>
                        <div>{{ task.brand_name }}</div>
                        <div>{{ task.submitter.name }}</div>
                        <div>{{ task.executor.name }}</div>
                        <div>{{ task.executor_credits }}</div>
                        <div class="!text-[68%]">{{ task.status }}</div>
                        <div>{{ task.submitter.demerit_points }}</div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
