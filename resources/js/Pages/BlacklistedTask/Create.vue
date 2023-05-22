<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";

import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const form = useForm({
    code: "",
    brand: "",
    country_id: "",
});

defineProps({
    countries: Object,
});

const submit = () => {
    form.post(route("blacklisted-tasks.store"));
};
</script>

<template>
    <AppLayout>
        <Head title="Create" />

        <h1 class="page-heading">Create</h1>

        <form @submit.prevent="submit" class="mx-auto sm:w-3/5">
            <div>
                <InputLabel for="country" value="Country" class="!font-bold" />

                <select
                    id="country"
                    v-model="form.country_id"
                    required
                    class="w-full pl-4 mt-1 border-gray-300 shadow-md rounded-3xl"
                >
                    <option value="" disabled>Select a country</option>
                    <option
                        v-for="country in countries"
                        :key="country.id"
                        :value="country.id"
                    >
                        {{ country.name }}
                    </option>
                </select>

                <InputError class="mt-2" :message="form.errors.country_id" />
            </div>

            <div class="mt-4">
                <InputLabel for="brand" value="Brand" class="!font-bold" />

                <TextInput
                    id="brand"
                    type="text"
                    class="block w-full mt-1"
                    placeholder="netflix"
                    v-model="form.brand"
                    required
                />

                <InputError class="mt-2" :message="form.errors.brand" />
            </div>

            <div class="mt-4">
                <InputLabel for="task-code" value="Code" class="!font-bold" />

                <TextInput
                    id="task-code"
                    type="text"
                    class="block w-full mt-1"
                    v-model="form.code"
                    placeholder="netflix_block"
                    required
                    autofocus
                />

                <InputError class="mt-2" :message="form.errors.code" />
            </div>

            <div class="flex justify-end mt-4">
                <PrimaryButton
                    class="ml-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Save
                </PrimaryButton>
            </div>
        </form>
    </AppLayout>
</template>
