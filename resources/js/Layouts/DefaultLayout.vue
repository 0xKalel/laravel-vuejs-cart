<script setup>
import Navbar from "@/Components/Organisms/Navbar.vue";
import Cart from "@/Components/Organisms/Cart.vue";
</script>

<template>
    <div>
        <Navbar></Navbar>
        <Cart></Cart>
        <!-- Page Heading -->
        <div class="min-h-screen bg-gray-100">
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>
            <!-- Page Content -->
            <main class="container mx-auto">
                <div class="container my-12 mx-auto px-4 md:px-12">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
