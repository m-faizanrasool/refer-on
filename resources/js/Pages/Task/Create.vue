<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const country = computed(() => usePage().props.country.data);

const form = useForm({
    country_id: country.value.id,
    brand: "",
    website: "",
    submitter_credits: "",
    executor_credits: "",
    task: "",
    summary: "",
});

const submit = () => {
    form.post(route("task.store"), {
        preserveScroll: true,
        wantsJson: true,
        onSuccess: (ad) => {
            console.log(ad);
        },
    });
};
</script>

<template>
    <Head title="Create Task" />

    <AppLayout>
        <div class="px-8 py-6 bg-gray-200 rounded-md">
            <div class="w-1/2 space-y-4">
                <div class="grid items-center grid-cols-2">
                    <InputLabel
                        class="!text-[20px] !font-bold"
                        for="name"
                        value="Country"
                    />

                    <div>
                        <TextInput
                            id="country"
                            type="text"
                            class="block w-full"
                            :placeholder="country.name"
                            required
                            disabled
                        />

                        <InputError
                            class="mt-2 ml-2"
                            :message="form.errors.country_id"
                        />
                    </div>
                </div>

                <div class="grid items-center grid-cols-2">
                    <InputLabel
                        class="!text-[20px] !font-bold"
                        for="brand"
                        value="Brand"
                    />

                    <div>
                        <TextInput
                            id="brand"
                            type="text"
                            class="block w-full"
                            v-model="form.brand"
                            placeholder="Nike"
                            required
                        />

                        <InputError
                            class="mt-2 ml-2"
                            :message="form.errors.brand"
                        />
                    </div>
                </div>

                <div class="grid items-center grid-cols-2">
                    <InputLabel
                        class="!text-[20px] !font-bold"
                        for="website"
                        value="Website"
                    />

                    <div>
                        <TextInput
                            id="website"
                            type="text"
                            class="block w-full"
                            v-model="form.website"
                            placeholder="www.example.com"
                            required
                        />

                        <InputError
                            class="mt-2 ml-2"
                            :message="form.errors.website"
                        />
                    </div>
                </div>

                <div class="grid items-center grid-cols-2">
                    <InputLabel
                        class="!text-[20px] !font-bold"
                        for="submitterCredit"
                        value="You will get"
                    />

                    <div>
                        <TextInput
                            id="submitterCredit"
                            type="number"
                            class="block w-full"
                            v-model="form.submitter_credits"
                            placeholder="$6"
                            required
                        />

                        <InputError
                            class="mt-2 ml-2"
                            :message="form.errors.submitter_credits"
                        />
                    </div>
                </div>

                <div class="grid items-center grid-cols-2">
                    <InputLabel
                        class="!text-[20px] !font-bold"
                        for="executorCredits"
                        value="Next user will get"
                    />

                    <div>
                        <TextInput
                            id="executorCredits"
                            type="number"
                            class="block w-full"
                            v-model="form.executor_credits"
                            placeholder="$5"
                            required
                        />

                        <InputError
                            class="mt-2 ml-2"
                            :message="form.errors.executor_credits"
                        />
                    </div>
                </div>

                <div class="grid items-center grid-cols-2">
                    <InputLabel
                        class="!text-[20px] !font-bold"
                        for="task"
                        value="Task"
                    />
                    <div>
                        <TextInput
                            id="task"
                            type="text"
                            class="block w-full"
                            v-model="form.task"
                            placeholder="task_874758"
                            required
                        />

                        <InputError
                            class="mt-2 ml-2"
                            :message="form.errors.task"
                        />
                    </div>
                </div>

                <div class="grid items-center grid-cols-2">
                    <InputLabel
                        class="!text-[20px] !font-bold"
                        for="summary"
                        value="Summary"
                    />

                    <div>
                        <textarea
                            name="summary"
                            id="country"
                            v-model="form.summary"
                            required
                            class="w-full pl-4 overflow-hidden border-gray-300 shadow-xl rounded-3xl"
                            placeholder="Nike is engaged in the design, development, manufacturing..."
                        ></textarea>

                        <InputError
                            class="mt-2 ml-2"
                            :message="form.errors.summary"
                        />
                    </div>
                </div>

                <div class="grid items-center grid-cols-2">
                    <div></div>

                    <div>
                        <button class="w-full btn btn-primary" @click="submit">
                            Proceed To Post
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
