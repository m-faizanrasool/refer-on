<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
    task: Object,
});
</script>

<template>
    <AppLayout>
        <Head title="Task" />

        <h1 class="page-heading">Successful Post</h1>

        <div class="grid sm:grid-cols-2">
            <div class="px-8 py-6 bg-gray-200 rounded-md">
                <div class="mb-4 text-2xl font-bold">
                    {{ task.brand.name }}
                </div>

                <div class="mb-4 text-lg font-bold">
                    Your code has been posted. Check My Account for more
                    details.
                </div>

                <div class="mb-8 sm:px-4">
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
                    You will get ${{ task.submitter_credits }}
                </div>

                <div class="text-lg font-bold">
                    Next user will get ${{ task.executor_credits }}
                </div>
            </div>

            <div class="flex flex-col p-8">
                <div>
                    <img src="images/logos/amazon.png" alt="" />
                </div>

                <div class="my-2 text-lg">{{ task.website }}</div>

                <p class="text-xl">
                    {{ task.summary }}
                </p>
            </div>
        </div>
    </AppLayout>
</template>
