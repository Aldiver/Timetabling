<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">
        <main
            class="container mx-auto flex-1 flex items-center justify-center px-4"
        >
            <div class="max-w-lg w-full">
                <h1 class="text-4xl md:text-5xl font-bold mb-6 text-center">
                    Don Pablo Lorenzo Memorial High School
                </h1>
                <p class="text-xl mb-8 text-center">
                    Welcome to our School Management System
                </p>

                <div v-if="canLogin" class="flex justify-center">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard.index')"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
                        >Dashboard</Link
                    >
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mr-4"
                            >Log in</Link
                        >

                        <!-- <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mr-4"
                            >Register</Link
                        > -->
                    </template>
                </div>
            </div>
        </main>
        <footer class="bg-gray-800 shadow">
            <div class="container mx-auto px-4 py-4 text-center">
                &copy; 2023 Don Pablo Lorenzo Memorial High School. All rights
                reserved.
            </div>
        </footer>
    </div>
</template>
