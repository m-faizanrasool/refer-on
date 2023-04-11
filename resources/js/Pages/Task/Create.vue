<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const countryCode = computed(() => usePage().props.country.data.countryCode);

const form = useForm({
    country_code: countryCode ?? "",
    brand: "",
    website: "",
    submitter_credits: "",
    executor_credits: "",
    task: "",
    summary: "",
});
</script>

<template>
    <Head title="Create Task" />

    <AppLayout>
        <div class="px-8 py-6 bg-gray-200 rounded-md">
            <div class="w-1/2 space-y-4">
                <div class="grid items-center grid-cols-2">
                    <InputLabel for="name" value="Country" />

                    <TextInput
                        id="country"
                        type="text"
                        class="block w-full"
                        v-model="form.country_code"
                        placeholder="Country"
                        required
                        disabled
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.country_code"
                    />
                </div>

                <div class="grid items-center grid-cols-2">
                    <InputLabel for="name" value="Brand" />

                    <TextInput
                        id="country"
                        type="text"
                        class="block w-full"
                        v-model="form.brand"
                        placeholder="Nike"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.brand" />
                </div>

                <div class="grid items-center grid-cols-2">
                    <InputLabel for="name" value="Website" />

                    <TextInput
                        id="country"
                        type="text"
                        class="block w-full"
                        v-model="form.website"
                        placeholder="www.example.com"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.website" />
                </div>

                <div class="grid items-center grid-cols-2">
                    <InputLabel for="name" value="You will get" />

                    <TextInput
                        id="country"
                        type="text"
                        class="block w-full"
                        v-model="form.submitter_credits"
                        placeholder="Country"
                        required
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.submitter_credits"
                    />
                </div>

                <div class="grid items-center grid-cols-2">
                    <InputLabel for="name" value="Next user will get" />

                    <TextInput
                        id="country"
                        type="text"
                        class="block w-full"
                        v-model="form.executor_credits"
                        placeholder="Country"
                        required
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.executor_credits"
                    />
                </div>

                <div class="grid items-center grid-cols-2">
                    <InputLabel for="name" value="Task" />

                    <TextInput
                        id="country"
                        type="text"
                        class="block w-full"
                        v-model="form.task"
                        placeholder="Country"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="grid items-center grid-cols-2">
                    <InputLabel for="name" value="Summary" />

                    <textarea
                        name="summary"
                        id="country"
                        v-model="form.summary"
                        required
                        class="pl-4 overflow-hidden border-gray-300 shadow-xl rounded-3xl"
                        placeholder="Nike is engaged in the design, development, manufacturing..."
                    ></textarea>

                    <InputError class="mt-2" :message="form.errors.summary" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
