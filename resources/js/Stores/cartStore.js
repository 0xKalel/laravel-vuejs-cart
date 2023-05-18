import { createStore } from "vuex";
import CartService from "@/Services/cartService.js";

const cartModule = {
    namespaced: true,
    state: {
        items: [],
        isOpen: false,
    },
    mutations: {
        setItems(state, items) {
            state.items = items;
        },
        addItem(state, item) {
            const existingItem = state.items.find((i) => i.id === item.id);

            if (existingItem) {
                if (existingItem.selectedQuantity < existingItem.quantity) {
                    existingItem.selectedQuantity++;
                }
            } else {
                item.selectedQuantity = 1;
                state.items.push(item);
            }
        },
        removeItem(state, itemIndex) {
            state.items.splice(itemIndex, 1);
        },
        clear(state) {
            state.items = {};
        },
        toggle(state) {
            state.isOpen = !state.isOpen;
        },
        incrementItem(state, itemIndex) {
            const item = state.items[itemIndex];
            if (item.selectedQuantity < item.quantity) {
                item.selectedQuantity++;
            }
        },
        decrementItem(state, itemIndex) {
            const item = state.items[itemIndex];
            if (item.selectedQuantity > 1) {
                item.selectedQuantity--;
            }
        },
        updateItem(state, { itemIndex, quantity }) {
            const item = state.items[itemIndex];
            item.selectedQuantity = parseInt(quantity);
        },
    },
    actions: {
        async add({ commit }, item) {
            try {
                await CartService.add(item);
                commit("addItem", item);
            } catch (error) {
                console.error(error);
                throw error;
            }
        },
        async remove({ commit, state }, itemIndex) {
            try {
                const productId = state.items[itemIndex].id;
                await CartService.remove(productId);
                commit("removeItem", itemIndex);
            } catch (error) {
                console.error(error);
                throw error;
            }
        },
        async increment({ commit, state }, itemIndex) {
            try {
                const productId = state.items[itemIndex].id;
                await CartService.increment(productId);
                commit("incrementItem", itemIndex);
            } catch (error) {
                console.error(error);
                throw error;
            }
        },
        async decrement({ commit, state }, itemIndex) {
            try {
                const productId = state.items[itemIndex].id;
                await CartService.increment(productId);
                commit("decrementItem", itemIndex);
            } catch (error) {
                console.error(error);
                throw error;
            }
        },
        set({ commit }, items) {
            commit("setItems", items);
        },
        toggle({ commit }) {
            commit("toggle");
        },
    },
    getters: {
        total(state) {
            return Object.values(state.items).reduce(
                (total, item) => total + item.selectedQuantity,
                0
            );
        },
        items(state) {
            return state.items;
        },
        isOpen(state) {
            return state.isOpen;
        },
        totalPrice(state) {
            return Object.values(state.items).reduce((total, item) => {
                return total + item.price * item.selectedQuantity;
            }, 0);
        },
    },
};

const store = createStore({
    modules: {
        cart: cartModule,
    },
    strict: process.env.NODE_ENV !== "production",
});

export default store;
