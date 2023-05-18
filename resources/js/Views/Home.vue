<script setup>
import { toRaw } from "vue";
import { useStore } from "vuex";
import { Head } from "@inertiajs/vue3";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import ProductsList from "@/Components/Organisms/ProductsList.vue";

const props = defineProps({
    products: Object,
    cartItems: Object,
});
const store = useStore();
const existingCartItems = toRaw(props.cartItems).data;
const products = toRaw(props.products).data;
store.dispatch("cart/set", existingCartItems);
</script>

<template>
    <Head title="Home" />
    <DefaultLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Products
            </h2>
        </template>
        <section class="mt-14 flex-1">
            <products-list :items="products"></products-list>
        </section>
    </DefaultLayout>
</template>
