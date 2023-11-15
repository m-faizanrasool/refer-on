<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import toastify from "toastify-js";

const copyCodeBlock = async () => {
    try {
        const text = props?.task?.code;

        await navigator.clipboard.writeText(text);

        toastify({
            text: "Code copied!",
            duration: 10000,
            close: true,
            stopOnFocus: true,
        }).showToast();
    } catch (error) {
        toastify({
            text: "Something went wrong!",
            className: "toastify-error",
            duration: 10000,
            close: true,
            stopOnFocus: true,
        }).showToast();
    }
};

const reportInvalid = (task_id) => {
    const message =
        "Kindly confirm with the brand/store that the referral code is invalid. Please keep a screenshot/documentary proof of the issue.";

    if (!confirm(message)) return;

    router.post(route("task.invalid"), {
        task_id: task_id,
    });
};

const getFullWebsiteUrl = (website) => {
    if (website.startsWith("http://") || website.startsWith("https://")) {
        return website;
    } else {
        return "http://" + website;
    }
};

const props = defineProps({
    task: Object,
    alreadyExists: Boolean,
});
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
                    {{ task.submitter.username }} will get ${{
                        task.brand.submitter_credits
                    }}.
                </div>

                <div class="relative mb-4 sm:px-4">
                    <TextInput
                        type="text"
                        class="block w-full mt-1 font-bold text-center !rounded-xl relative"
                        readonly
                        :value="task.code"
                    />

                    <button
                        class="absolute top-0 bottom-0 right-6"
                        @click="copyCodeBlock"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-6 h-6"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"
                            />
                        </svg>
                    </button>
                </div>

                <div class="text-lg font-bold">
                    {{ alreadyExists ? "Next user" : "You" }} will get ${{
                        task.brand.executor_credits
                    }}.
                </div>

                <div class="mt-4 space-y-4 sm:mx-8" v-if="!alreadyExists">
                    <Link
                        class="block w-full text-center btn btn-primary"
                        :href="route('task.fulfill', task.id)"
                    >
                        Proceed to fulfil
                    </Link>

                    <button
                        class="w-full font-bold border border-black btn"
                        @click="reportInvalid(task.id)"
                    >
                        Report invalid task
                    </button>
                </div>

                <div v-else class="mt-4 text-red-600">
                    <div>
                        Our records show that you have already submitted a code
                        for {{ task.brand.name }}.
                    </div>

                    <div>
                        Please note that you can only make one code submission
                        per brand.
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center p-8">
                <div>
                    <img src="images/logo_default.svg" alt="logo default" />
                </div>

                <a
                    class="my-2 text-xl hover:underline"
                    :href="getFullWebsiteUrl(task.brand.website)"
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
