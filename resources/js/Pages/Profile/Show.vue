<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";

import { Head, Link } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Account" />

    <AppLayout>
        <h1 class="page-heading">Account Overview</h1>

        <div class="grid gap-8 sm:grid-cols-2">
            <div class="px-8 py-6 space-y-4 bg-gray-200 rounded-md">
                <div
                    class="grid items-center font-bold gap-y-2 gap-x-4 md:grid-cols-2"
                >
                    Tasks that you fulfilled.

                    <TextInput
                        id="otp"
                        type="number"
                        class="block text-center"
                        placeholder="$5"
                        readonly
                    />
                </div>

                <div
                    class="grid items-center font-bold gap-y-2 gap-x-4 md:grid-cols-2"
                >
                    Your earings

                    <TextInput
                        id="otp"
                        type="number"
                        class="block text-center"
                        placeholder="$5"
                        readonly
                    />
                </div>
            </div>

            <div class="px-8 py-6 space-y-4 bg-gray-200 rounded-md">
                <div
                    class="grid items-center font-bold gap-y-2 gap-x-4 md:grid-cols-2"
                >
                    Your tasks that other fulfilled.

                    <TextInput
                        id="otp"
                        type="number"
                        class="block text-center"
                        placeholder="$5"
                        readonly
                    />
                </div>

                <div
                    class="grid items-center font-bold gap-y-2 gap-x-4 md:grid-cols-2"
                >
                    Your earings

                    <TextInput
                        id="otp"
                        type="number"
                        class="block text-center"
                        placeholder="$5"
                        readonly
                    />
                </div>
            </div>
        </div>

        <div class="flex items-center mt-6">
            <Link class="mx-auto btn btn-primary" :href="route('profile.edit')"
                >Details</Link
            >
        </div>
    </AppLayout>
</template>
