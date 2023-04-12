<script setup>
import intlTelInput from "intl-tel-input";

import AppLayout from "@/Layouts/AppLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { computed, onMounted, reactive, ref } from "vue";

import { initializeApp } from "firebase/app";

import {
    getAuth,
    signInWithPhoneNumber,
    RecaptchaVerifier,
} from "firebase/auth";

const state = reactive({
    firebaseApp: null,
    firebaseAuth: null,
    firebaseAppVerifier: null,
    confirmationResult: null,
});

let country = computed(() => usePage().props.country.data);

const phoneInput = ref();
const msgSuccess = ref("");
const otp = ref();
const otpVerified = ref(false);

const initFirebase = () => {
    const firebaseConfig = {
        apiKey: "AIzaSyDfDCr2SNQxCKI4r1yE1UAvOKMXjspNm98",
        authDomain: "referon88.firebaseapp.com",
        projectId: "referon88",
        storageBucket: "referon88.appspot.com",
        messagingSenderId: "386839669623",
        appId: "1:386839669623:web:932dd3476837bc73eacf72",
        measurementId: "G-GXVLYQJ00C",
    };

    state.firebaseApp = initializeApp(firebaseConfig);
    state.firebaseAuth = getAuth(state.firebaseApp);
};

const initRecaptcha = () => {
    state.firebaseAppVerifier = new RecaptchaVerifier(
        "recaptcha-container",
        {
            size: "normal",
            callback: (response) => {
                // reCAPTCHA solved, allow signInWithPhoneNumber.
                sendOTP();
            },
            "expired-callback": () => {
                // Response expired. Ask user to solve reCAPTCHA again.
            },
        },
        state.firebaseAuth
    );

    state.firebaseAppVerifier.render().then((widgetId) => {
        const recaptchaResponse = grecaptcha.getResponse(widgetId);
    });
};

const sendOTP = () => {
    signInWithPhoneNumber(
        state.firebaseAuth,
        phoneInput.value.getNumber(),
        state.firebaseAppVerifier
    )
        .then((confirmationResult) => {
            state.confirmationResult = confirmationResult;

            msgSuccess.value = "Message sent successfully.";
            state.firebaseAppVerifier.clear();
        })
        .catch((error) => {
            console.log("error", error.message);
        });
};

const otpVerify = () => {
    state.confirmationResult
        .confirm(otp.value)
        .then((result) => {
            // User signed in successfully.
            const user = result.user;
            console.log("success", "You are successfully logged in.");

            otpVerified.value = true;
        })
        .catch((error) => {
            console.log("error", error.message);
        });
};

onMounted(() => {
    initFirebase();

    const input = document.querySelector("#phone");

    phoneInput.value = intlTelInput(input, {
        utilsScript:
            "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.0/js/utils.min.js",
        separateDialCode: true,
        allowDropdown: false,
    });

    phoneInput.value.setCountry(country.value.code);
});

const onSignInSubmit = () => {
    if (!phoneInput.value.isValidNumber()) {
        form.setError("phone", "Please enter a valid phone number");

        return;
    }

    form
        .transform((data) => ({
            ...data,
            phone: phoneInput.value.getNumber(),
        }))
        .post("register-validate", {
            onError: () => {
                form.reset("password", "password_confirmation");
            },
            onSuccess: () => {
                initRecaptcha();
            },
        });
};

let form = useForm({
    name: "",
    email: "",
    phone: "",
    password: "",
    country_id: country.value.id,
    password_confirmation: "",
    terms: false,
});

const submit = () => {
    if (otpVerified) {
        form.post(route("register"), {
            onFinish: () => form.reset("password", "password_confirmation"),
        });
    }
};
</script>

<template>
    <AppLayout>
        <Head title="Register" />

        <h1 class="page-heading">Register</h1>

        <div class="w-3/5 mx-auto">
            <div class="flex gap-x-10">
                <div class="flex flex-col w-full gap-4">
                    <div>
                        <InputLabel for="name" value="Country" />

                        <TextInput
                            id="country"
                            type="text"
                            class="block w-full mt-1"
                            :placeholder="country.name"
                            required
                            disabled
                        />
                    </div>

                    <div>
                        <InputLabel for="name" value="Name*" />

                        <TextInput
                            id="name"
                            type="text"
                            class="block w-full mt-1"
                            v-model="form.name"
                            placeholder="John Doe"
                            :disabled="form.wasSuccessful"
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
                            type="text"
                            class="block w-full mt-1"
                            :disabled="form.wasSuccessful"
                            v-model="form.phone"
                            required
                            autocomplete="phone"
                        />

                        <InputError class="mt-2" :message="form.errors.phone" />
                    </div>

                    <div>
                        <div class="space-y-4">
                            <button
                                class="w-full btn btn-primary"
                                @click="onSignInSubmit()"
                            >
                                Request captcha and OTP
                            </button>

                            <div id="recaptcha-container"></div>
                        </div>

                        <div class="flex gap-2 mt-4" v-if="form.wasSuccessful">
                            <TextInput
                                id="otp"
                                type="number"
                                class="block w-full mt-1"
                                placeholder="OTP"
                                v-model="otp"
                                autocomplete="otp"
                            />

                            <button
                                class="w-full btn btn-primary"
                                @click="otpVerify()"
                            >
                                Verify Otp
                            </button>
                        </div>

                        <div
                            v-text="msgSuccess"
                            class="mt-3 ml-2 text-green-600"
                        ></div>
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
                            :disabled="form.wasSuccessful"
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
                            :disabled="form.wasSuccessful"
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
                            :disabled="form.wasSuccessful"
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
                    :disabled="!otpVerified"
                    @click="submit()"
                >
                    Register
                </button>
            </div>
        </div>
    </AppLayout>
</template>
