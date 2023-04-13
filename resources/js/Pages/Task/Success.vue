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

        <div class="grid grid-cols-2">
            <div class="px-8 py-6 bg-gray-200 rounded-md">
                <div class="mb-4 text-2xl font-bold color-primary">
                    {{ task?.brand?.name ?? "Brand Name" }}
                </div>

                <div class="mb-4 text-lg font-bold color-primary">
                    Your task has been post. Check My Account for more detail
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
                    You will get ${{ task.submitter_credits }}
                </div>

                <div class="text-lg font-bold color-primary">
                    Next user will get ${{ task.executor_credits }}
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
