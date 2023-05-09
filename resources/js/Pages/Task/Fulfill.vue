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
    code: "",
    task_id: task_id.value,
});

const complete = () => {
    if (!form.code) {
        form.setError("code", "Code field is required");
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
                        :value="task.code"
                        v-on:focus="$event.target.select()"
                    />
                </div>

                <div class="mb-4 text-lg font-bold">
                    You got ${{ task.brand.executor_credits }}.
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
                        v-model="form.code"
                    />

                    <InputError class="mt-2" :message="form.errors.code" />
                </div>

                <!--
                <div
                    class="ml-2 font-medium text-red-500"
                    v-if="$page.props.flash.error"
                >
                    <div v-if="$page.props.flash.error.includes('blacklisted')">
                        You have submitted a Blacklisted code.<br />
                        You will receive 1 demerit point. <br />
                        Violation of our Terms shall lead to suspension or
                        termination.
                    </div>

                    <div v-else>
                        {{ $page.props.flash.error }}
                    </div>
                </div> -->

                <div class="flex justify-end my-4 md:px-4">
                    <button class="btn btn-primary" @click="complete">
                        Proceed to post
                    </button>
                </div>
            </div>

            <div class="flex flex-col items-center p-8">
                <div>
                    <img src="images/logo_default.svg" alt="" />
                </div>

                <a
                    class="my-2 text-xl hover:underline"
                    :href="task.brand.website"
                    target="_blank"
                    >{{ task.brand.website }}</a
                >

                <p class="text-xl">
                    {{ task.brand.summary }}
                </p>
            </div>
        </div>
    </AppLayout>
</template>
