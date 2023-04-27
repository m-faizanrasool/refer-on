<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

let task = computed(() => usePage().props.task);

const form = useForm({
    country_id: task.value.country.id,
    brand: task.value.brand.name,
    website: task.value.website,
    submitter_credits: task.value.submitter_credits,
    executor_credits: task.value.executor_credits,
    task: task.value.key,
    summary: task.value.summary,
});

const submit = () => {
    form.post(route("task.update"), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Create Task" />

    <AppLayout>
        <h1 class="page-heading">Update Task</h1>

        <div class="px-8 py-6 bg-gray-200 rounded-md">
            <div class="mx-auto space-y-4 sm:w-1/2">
                <div>
                    <InputLabel
                        class="text-lg !font-bold"
                        for="name"
                        value="Country"
                    />

                    <TextInput
                        id="country"
                        type="text"
                        class="block w-full"
                        :placeholder="task.country.name"
                        required
                        disabled
                    />

                    <InputError
                        class="!mt-1.5 ml-2"
                        :message="form.errors.country_id"
                    />
                </div>

                <div>
                    <InputLabel
                        class="text-lg !font-bold"
                        for="brand"
                        value="Brand"
                    />

                    <TextInput
                        id="brand"
                        type="text"
                        class="block w-full"
                        v-model="form.brand"
                        placeholder="e.g. Netflix"
                        required
                    />

                    <InputError
                        class="!mt-1.5 ml-2"
                        :message="form.errors.brand"
                    />
                </div>

                <div>
                    <InputLabel
                        class="text-lg !font-bold"
                        for="website"
                        value="Website"
                    />

                    <TextInput
                        id="website"
                        type="text"
                        class="block w-full"
                        v-model="form.website"
                        placeholder="e.g. www.netflix.com"
                        required
                    />

                    <InputError
                        class="!mt-1.5 ml-2"
                        :message="form.errors.website"
                    />
                </div>

                <div>
                    <InputLabel
                        class="text-lg !font-bold"
                        for="summary"
                        value="Summary"
                    />

                    <textarea
                        name="summary"
                        id="country"
                        v-model="form.summary"
                        required
                        class="w-full pl-4 overflow-hidden border-gray-300 shadow-xl rounded-3xl"
                        placeholder="Netflix is a subscription-based video streaming service"
                    ></textarea>

                    <InputError
                        class="!mt-1.5 ml-2"
                        :message="form.errors.summary"
                    />
                </div>

                <div>
                    <InputLabel
                        class="text-lg !font-bold"
                        for="submitterCredit"
                        value="You will get"
                    />

                    <TextInput
                        id="submitterCredit"
                        type="number"
                        class="block w-full"
                        v-model="form.submitter_credits"
                        placeholder="$6"
                        required
                    />

                    <InputError
                        class="!mt-1.5 ml-2"
                        :message="form.errors.submitter_credits"
                    />
                </div>

                <div>
                    <InputLabel
                        class="text-lg !font-bold"
                        for="executorCredits"
                        value="Next user will get"
                    />

                    <TextInput
                        id="executorCredits"
                        type="number"
                        class="block w-full"
                        v-model="form.executor_credits"
                        placeholder="$5"
                        required
                    />

                    <InputError
                        class="!mt-1.5 ml-2"
                        :message="form.errors.executor_credits"
                    />
                </div>

                <div>
                    <InputLabel
                        class="text-lg !font-bold"
                        for="task"
                        value="Your Code"
                    />

                    <TextInput
                        id="task"
                        type="text"
                        class="block w-full"
                        v-model="form.task"
                        placeholder="e.g. netflix_code_123"
                        required
                    />

                    <InputError
                        class="!mt-1.5 ml-2"
                        :message="form.errors.task"
                    />
                </div>

                <button class="w-full btn btn-primary" @click="submit">
                    Proceed To Post
                </button>
            </div>
        </div>
    </AppLayout>
</template>