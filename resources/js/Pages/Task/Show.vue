<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

const taskKey = ref(null);

defineProps({
    task: Object,
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
                    {{ task.submitter.name }} will get ${{
                        task.submitter_credits
                    }}
                </div>

                <div class="mb-4 sm:px-4">
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

                <div class="text-lg font-bold">
                    You will get ${{ task.executor_credits }}
                </div>

                <div class="mt-4 space-y-4 sm:mx-8">
                    <Link
                        class="block w-full text-center btn btn-primary"
                        :href="route('task.fulfill', task.id)"
                    >
                        Proceed to fullfill
                    </Link>

                    <button class="w-full font-bold border border-black btn">
                        Report invalid task
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