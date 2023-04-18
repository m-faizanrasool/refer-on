<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";

import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { onMounted, ref, computed } from "vue";

import intlTelInput from "intl-tel-input";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

let country = computed(() => usePage().props.country.data);

const phoneInput = ref();

onMounted(() => {
    const input = document.querySelector("#phone");

    phoneInput.value = intlTelInput(input, {
        utilsScript:
            "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.0/js/utils.min.js",
        separateDialCode: true,
        allowDropdown: false,
    });

    phoneInput.value.setCountry(country.value.code);
});

const form = useForm({
    phone: "",
    password: "",
    remember: false,
});

const submit = () => {
    if (!phoneInput.value.isValidNumber()) {
        form.setError("phone", "Please enter a valid phone number");

        return;
    }

    form.transform((data) => ({
        ...data,
        phone: phoneInput.value.getNumber(),
    })).post(route("login"), {
        onError: () => {
            form.reset("password");
        },
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Login" />

        <h1 class="page-heading">Login</h1>

        <form @submit.prevent="submit" class="mx-auto sm:w-3/5">
            <div>
                <InputLabel for="phone" value="Mobile Number" />

                <div class="mt-1">
                    <TextInput
                        id="phone"
                        type="text"
                        class="block w-full"
                        v-model="form.phone"
                        required
                        autofocus
                        autocomplete="phone"
                    />
                </div>

                <InputError class="mt-2" :message="form.errors.phone" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="block w-full mt-1"
                    v-model="form.password"
                    placeholder="*******"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none"
                >
                    Forgot your password?
                </Link>
            </div>

            <div class="my-4">
                <Link
                    :href="route('register')"
                    class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none"
                >
                    Not a User? Register
                </Link>
            </div>

            <div class="flex justify-end">
                <button class="btn btn-primary" type="submit">Login</button>
            </div>
        </form>
    </AppLayout>
</template>
