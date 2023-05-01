<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";

import { Head, Link } from "@inertiajs/vue3";

defineProps({
    fulfilledTasks: Number,
    fulfilledTasksEarnings: Number,
    tasksFulfilledByOthers: Number,
    tasksFulfilledByOthersEarnings: Number,
});
</script>

<template>
    <Head title="Account" />

    <AppLayout>
        <div class="flex items-center justify-between mb-8">
            <h1 class="m-0 page-heading">Account Overview</h1>

            <Link
                class="block px-14 btn btn-success"
                as="button"
                :href="route('profile.edit')"
                >Edit Account</Link
            >
        </div>

        <div class="grid gap-8 sm:grid-cols-2">
            <div class="px-8 py-6 space-y-4 bg-gray-200 rounded-md">
                <div
                    class="grid items-center font-bold gap-y-2 gap-x-4 md:grid-cols-2"
                >
                    Codes that you fulfilled

                    <TextInput
                        id="otp"
                        type="number"
                        class="block text-center lg:ml-24"
                        :value="fulfilledTasks"
                        readonly
                    />
                </div>

                <div
                    class="grid items-center font-bold gap-y-2 gap-x-4 md:grid-cols-2"
                >
                    Your earnings

                    <TextInput
                        type="text"
                        class="block text-center lg:ml-24"
                        :value="`$${fulfilledTasksEarnings}`"
                        readonly
                    />
                </div>
            </div>

            <div class="px-8 py-6 space-y-4 bg-gray-200 rounded-md">
                <div
                    class="grid items-center font-bold gap-y-2 gap-x-4 md:grid-cols-2"
                >
                    Your codes that other users fulfilled

                    <TextInput
                        type="number"
                        class="block text-center lg:ml-24"
                        :value="tasksFulfilledByOthers"
                        readonly
                    />
                </div>

                <div
                    class="grid items-center font-bold gap-y-2 gap-x-4 md:grid-cols-2"
                >
                    Your earnings

                    <TextInput
                        id="otp"
                        type="text"
                        class="block text-center lg:ml-24"
                        :value="`$${tasksFulfilledByOthersEarnings}`"
                        readonly
                    />
                </div>
            </div>
        </div>

        <Link
            class="block mx-auto mt-8 text-center px-14 btn btn-primary"
            as="button"
            :href="route('profile.detail')"
            >Details</Link
        >
    </AppLayout>
</template>
