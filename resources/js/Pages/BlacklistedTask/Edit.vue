<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

let blacklistedTask = computed(() => usePage().props.blacklistedTask);

const form = useForm({
    key: blacklistedTask.value.key,
    brand: blacklistedTask.value.brand.name,
});

const submit = () => {
    form.post(route("blacklisted-tasks.update", blacklistedTask.value.id), {
        onFinish: () => {},
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Edit" />

        <h1 class="page-heading">Edit</h1>

        <form @submit.prevent="submit" class="mx-auto sm:w-3/5">
            <div>
                <InputLabel for="task" value="Task" class="!font-bold" />

                <TextInput
                    id="task"
                    type="text"
                    class="block w-full mt-1"
                    v-model="form.key"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.key" />
            </div>

            <div class="mt-4">
                <InputLabel for="brand" value="Brand" class="!font-bold" />

                <TextInput
                    id="brand"
                    type="text"
                    class="block w-full mt-1"
                    v-model="form.brand"
                    required
                    autocomplete="current-brand"
                />

                <InputError class="mt-2" :message="form.errors.brand" />
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
