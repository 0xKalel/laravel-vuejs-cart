import axios from "axios";

const CartService = {
    async add(item) {
        try {
            const response = await axios.post("/cart/add", item);
            return response.data;
        } catch (error) {
            throw new Error("Failed to add item to cart.");
        }
    },

    async remove(product_id) {
        try {
            const response = await axios.delete(`/cart/remove/${product_id}`);
            return response.data;
        } catch (error) {
            throw new Error("Failed to remove item from cart.");
        }
    },

    async increment(product_id) {
        try {
            const response = await axios.put(`/cart/increment/${product_id}`);
            return response.data;
        } catch (error) {
            throw new Error("Failed to increment item quantity.");
        }
    },

    async decrement(product_id) {
        try {
            const response = await axios.put(`/cart/decrement/${product_id}`);
            return response.data;
        } catch (error) {
            throw new Error("Failed to decrement item quantity.");
        }
    },
};

export default CartService;
