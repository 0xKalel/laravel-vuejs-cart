<script setup>
import { computed } from "vue";
import ProductCard from "@/Components/Molecules/ProductCard.vue";

defineProps({
    items: Array,
});
</script>
<template>
    <div class="flex flex-wrap -mx-1 lg:-mx-4">
        <div
            v-for="(item, index) in items"
            class="my-1 px-1 w-full md:w-1/2 lg:my-4 lg:px-4 lg:w-1/3"
        >
            <product-card :key="index" :data="item" />
        </div>
    </div>
</template>
