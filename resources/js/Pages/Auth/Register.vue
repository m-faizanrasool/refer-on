<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const countryCode = computed(() => usePage().props.country.data.countryCode);

const form = useForm({
    name: "",
    email: "",
    phone: "",
    password: "",
    country_code: countryCode ?? "",
    password_confirmation: "",
    terms: false,
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <h1 class="page-heading">Register</h1>

        <form @submit.prevent="submit" class="w-3/5 mx-auto">
            <div class="flex gap-x-10">
                <div class="flex flex-col w-full gap-4">
                    <div>
                        <InputLabel for="name" value="Country" />

                        <TextInput
                            id="country"
                            type="text"
                            class="block w-full mt-1"
                            v-model="form.country_code"
                            placeholder="Country"
                            required
                            disabled
                        />

                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="name" value="Name*" />

                        <TextInput
                            id="name"
                            type="text"
                            class="block w-full mt-1"
                            v-model="form.name"
                            placeholder="John Doe"
                            required
                            autofocus
                            autocomplete="name"
                        />

                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="email" value="Phone Number*" />

                        <TextInput
                            id="phone"
                            type="number"
                            class="block w-full mt-1"
                            placeholder="0316 68838784"
                            v-model="form.phone"
                            required
                            autocomplete="phone"
                        />

                        <InputError class="mt-2" :message="form.errors.phone" />
                    </div>

                    <div>
                        <button class="w-full btn btn-primary">
                            Request captcha and OTP
                        </button>

                        <div class="flex gap-2 mt-4">
                            <TextInput
                                id="otp"
                                type="number"
                                class="block w-full mt-1"
                                placeholder="OTP"
                                v-model="form.otp"
                                autocomplete="otp"
                            />

                            <button class="w-full btn btn-primary">
                                Verify Otp
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col w-full gap-4">
                    <div>
                        <InputLabel for="email" value="Email*" />

                        <TextInput
                            id="email"
                            type="email"
                            class="block w-full mt-1"
                            placeholder="johndoe@example.com"
                            v-model="form.email"
                            required
                            autocomplete="username"
                        />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <InputLabel for="password" value="Password*" />

                        <TextInput
                            id="password"
                            type="password"
                            class="block w-full mt-1"
                            v-model="form.password"
                            placeholder="********"
                            required
                            autocomplete="new-password"
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.password"
                        />
                    </div>

                    <div>
                        <InputLabel
                            for="password_confirmation"
                            value="Confirm Password*"
                        />

                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="block w-full mt-1"
                            v-model="form.password_confirmation"
                            placeholder="********"
                            required
                            autocomplete="new-password"
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.password_confirmation"
                        />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="text-sm text-gray-600 underline rounded-md hover:text-gray-900"
                >
                    Already registered?
                </Link>

                <button
                    class="ml-4 btn btn-primary"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    type="submit"
                >
                    Register
                </button>
            </div>
        </form>
    </GuestLayout>
</template>
