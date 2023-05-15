<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, router } from "@inertiajs/vue3";

const reportInvalid = (task_id) => {
    const message =
        "Kindly confirm with the brand/store if your referral code has been used. Please keep a screenshot/documentary proof of the issue.";

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

defineProps({
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

                <div class="mb-4 sm:px-4">
                    <TextInput
                        id="country"
                        type="text"
                        class="block w-full mt-1 font-bold text-center !rounded-xl"
                        readonly
                        :value="task.code"
                        v-on:focus="$event.target.select()"
                    />
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
                        Proceed to fullfill
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
