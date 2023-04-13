<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import { ref, computed } from "vue";

const taskCompleted = ref(false);

let task_id = computed(() => usePage().props.task.id);

defineProps({
    task: Object,
});

let form = useForm({
    key: "",
    task_id: task_id.value,
});

const complete = () => {
    if (!form.key) {
        form.setError("key", "Task field is required");
        return;
    }
    form.post(route("task.complete"), {
        preserveScroll: true,
        onSuccess: () => {
            taskCompleted.value = true;
        },
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Task" />

        <div class="grid grid-cols-2">
            <div class="px-8 py-6 bg-gray-200 rounded-md">
                <div class="mb-4 text-2xl font-bold color-primary">
                    {{ task?.brand?.name ?? "Brand Name" }}
                </div>

                <div class="mb-4 text-lg font-bold color-primary">
                    Thank you for completing the job.
                </div>

                <div class="px-4 mb-4">
                    <TextInput
                        id="country"
                        type="text"
                        class="block w-full mt-1 font-bold text-center !rounded-xl color-primary"
                        readonly
                        ref="taskKey"
                        :value="task.key"
                        v-on:focus="$event.target.select()"
                    />
                </div>

                <div class="mb-4 text-lg font-bold color-primary">
                    You got ${{ task.executor_credits }}
                </div>

                <div class="mb-4 text-lg font-bold color-primary">
                    Please key in your task.
                </div>

                <div class="px-4 mb-4">
                    <TextInput
                        id="country"
                        type="text"
                        class="block w-full mt-1 font-bold !rounded-xl color-primary"
                        placeholder="task_78767"
                        v-model="form.key"
                    />

                    <InputError class="mt-2" :message="form.errors.key" />
                </div>

                <div class="mx-4 my-4">
                    <button class="w-full btn btn-primary" @click="complete">
                        Proceed to post
                    </button>
                </div>
            </div>

            <div class="flex flex-col items-center justify-center p-8">
                <img src="images/logos/amazon.png" alt="" />

                <div class="my-3">{{ task.website }}</div>

                <p>
                    {{ task.summary }}
                </p>
            </div>
        </div>
    </AppLayout>
</template>
