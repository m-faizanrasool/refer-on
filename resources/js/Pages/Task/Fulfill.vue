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

        <div class="grid sm:grid-cols-2">
            <div class="px-8 py-6 bg-gray-200 rounded-md">
                <div class="mb-4 text-2xl font-bold">
                    {{ task.brand.name }}
                </div>

                <div class="mb-4 text-lg font-bold">
                    Thank you for using the code.
                </div>

                <div class="mb-4 md:px-4">
                    <TextInput
                        id="country"
                        type="text"
                        class="block w-full mt-1 font-bold text-center !rounded-xl"
                        readonly
                        ref="taskKey"
                        :value="task.key"
                        v-on:focus="$event.target.select()"
                    />
                </div>

                <div class="mb-4 text-lg font-bold">
                    You got ${{ task.executor_credits }}.
                </div>

                <div class="mb-4 text-lg font-bold">
                    Please key in your new referral code. We'll share it with
                    the next user.
                </div>

                <div class="mb-4 md:px-4">
                    <TextInput
                        id="country"
                        type="text"
                        class="block w-full mt-1 font-bold !rounded-xl"
                        v-model="form.key"
                    />

                    <InputError class="mt-2" :message="form.errors.key" />
                </div>

                <div class="flex justify-end my-4 md:px-4">
                    <button class="btn btn-primary" @click="complete">
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
