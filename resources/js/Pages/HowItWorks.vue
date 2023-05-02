<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<template>
    <AppLayout>
        <h1 class="page-heading">How it works</h1>

        <div>
            <p class="mb-4">Welcome to the community!</p>

            <p class="mb-6">
                Get double benefits from every referral program in 3 simple
                steps.
            </p>

            <div>
                <div class="grid gap-3 mb-12 md:grid-cols-2">
                    <div>
                        <h6 class="mb-4 text-lg font-bold">1. Log in</h6>
                        <p>Log in your Referon account.</p>
                    </div>

                    <div>
                        <img src="images/login.png" />
                    </div>
                </div>

                <div class="grid gap-3 mb-12 md:grid-cols-2">
                    <div>
                        <h6 class="mb-4 text-lg font-bold">
                            2. Search and use referral codes
                        </h6>

                        <div class="flex flex-col h-full">
                            <p class="mb-4">
                                Key in the brand/merchant name to search for
                                it's referral code
                            </p>

                            <p class="md:mt-auto md:mb-12">
                                Use the referral code that we present to you to
                                sign up/purchase with the brand/merchant. Once
                                you are done, click “Proceed to fulfil”
                            </p>
                        </div>
                    </div>

                    <div>
                        <img src="images/searchandusecode.png" />
                    </div>
                </div>

                <div class="grid gap-3 mb-12 md:grid-cols-2">
                    <div>
                        <h6 class="mb-4 text-lg font-bold">
                            3. Share your referral codes
                        </h6>

                        <p>
                            You can now submit your own referral code to Referon
                            for the next user to use
                        </p>
                    </div>

                    <div>
                        <img src="images/submitcode.png" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
