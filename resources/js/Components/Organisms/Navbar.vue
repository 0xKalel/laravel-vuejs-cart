<script setup>
import { ref, computed, toRaw } from "vue";
import { Link } from "@inertiajs/vue3";
import { useStore } from "vuex";
import Dropdown from "@/Components/Atoms/Dropdown.vue";
import DropdownLink from "@/Components/Atoms/DropdownLink.vue";

const store = useStore();
const showMenu = ref(false);
const totalCartItems = computed(() => toRaw(store.getters["cart/total"]));

function toggleNavbar() {
    showMenu.value = !showMenu.value;
}
const toggleCart = () => {
    store.dispatch("cart/toggle");
};
</script>
<template>
    <nav
        class="relative flex flex-wrap items-center justify-between px-2 py-3 bg-slate-500"
    >
        <div
            class="container px-4 mx-auto flex flex-wrap items-center justify-between"
        >
            <div
                class="w-full relative flex justify-between lg:w-auto px-4 lg:static lg:block lg:justify-start"
            >
                <Link
                    :href="route('home')"
                    class="text-sm font-bold leading-relaxed inline-block mr-4 py-2 whitespace-nowrap uppercase text-white"
                >
                    Brasket Project <small class="text-xs">By Khalil</small>
                </Link>
                <button
                    class="text-white cursor-pointer text-xl leading-none px-3 py-1 border border-solid border-transparent rounded bg-transparent block lg:hidden outline-none focus:outline-none"
                    type="button"
                    v-on:click="toggleNavbar()"
                >
                    <svg
                        height="32px"
                        fill="white"
                        style="
                            enable-background: new 0 0 32 32;
                            border-color: white;
                            color: white;
                        "
                        version="1.1"
                        viewBox="0 0 32 32"
                        width="32px"
                    >
                        <path
                            d="M4,10h24c1.104,0,2-0.896,2-2s-0.896-2-2-2H4C2.896,6,2,6.896,2,8S2.896,10,4,10z M28,14H4c-1.104,0-2,0.896-2,2 s0.896,2,2,2h24c1.104,0,2-0.896,2-2S29.104,14,28,14z M28,22H4c-1.104,0-2,0.896-2,2s0.896,2,2,2h24c1.104,0,2-0.896,2-2 S29.104,22,28,22z"
                        />
                    </svg>
                </button>
            </div>

            <div
                v-bind:class="{ hidden: !showMenu, flex: showMenu }"
                class="lg:flex lg:flex-grow items-center"
            >
                <ul
                    class="flex flex-col lg:flex-row list-none ml-auto items-center"
                >
                    <li class="nav-item" v-if="!$page.props.auth.user">
                        <Link
                            :href="route('login')"
                            class="px-3 py-2 flex items-center text-xs uppercase font-bold leading-snug text-white hover:opacity-75"
                        >
                            <i
                                class="fab fa-facebook-square text-lg leading-lg text-white opacity-75"
                            /><span class="ml-2">Login</span>
                        </Link>
                    </li>
                    <li class="nav-item" v-if="!$page.props.auth.user">
                        <Link
                            :href="route('register')"
                            class="px-3 py-2 flex items-center text-xs uppercase font-bold leading-snug text-white hover:opacity-75"
                        >
                            <i
                                class="fab fa-twitter text-lg leading-lg text-white opacity-75"
                            /><span class="ml-2">Register</span>
                        </Link>
                    </li>
                    <li class="nav-item">
                        <a
                            @click="toggleCart()"
                            class="px-3 py-2 flex items-center text-xs uppercase font-bold leading-snug text-white hover:opacity-75"
                            href="#"
                        >
                            <span class="ml-2 relative flex"
                                ><svg
                                    class="h-6 w-6 flex-shrink-0 text-white group-hover:text-white"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    aria-hidden="true"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"
                                    ></path>
                                </svg>
                                <span
                                    v-if="totalCartItems > 0"
                                    class="bg-green-100 custom-notification text-green-800 absolute right-0 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300"
                                    >{{ totalCartItems }}</span
                                >
                            </span>
                        </a>
                    </li>
                    <li v-if="$page.props.auth.user">
                        <div class="ml-3 relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white hover:opacity-75 focus:outline-none transition ease-in-out duration-150"
                                        >
                                            {{ $page.props.auth.user.name }}

                                            <svg
                                                class="ml-2 -mr-0.5 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')">
                                        Profile
                                    </DropdownLink>
                                    <DropdownLink
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                    >
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
<style scoped>
.custom-notification {
    position: absolute;
    padding: 3px;
    bottom: -10px;
    right: -20px;
}
</style>
