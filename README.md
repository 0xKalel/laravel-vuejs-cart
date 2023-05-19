# Laravel Breeze, Vue.js Cart System

This project is a shopping basket solution that enables synchronized cart management, merges data from different platforms upon login, and tracks removed items before checkout for a seamless shopping experience.

## Technologies Used

- Laravel Breeze
- Vue.js 3 composition API
- Vuex
- Inertia.js
- Tailwind CSS

## Installation

Follow the steps below to install and set up the project locally:

1. Install Composer dependencies:
```console
composer install
```
2. Install Node dependencies:
```console
npm install
```
3. Generate Laravel Key:
```console
php artisan key:generate
```
4. Migrate the database and seed to see the list of products:
```console
php artisan migrate --seed
```
5. Compile assets:
```console
npm run dev
```
6. Run the Laravel local development server:
```console
php artisan serve
```
Once the server is running, you can access the application in your web browser at `http://localhost:8000`.

## Usage

1. Click on "Add to Cart" to add a product to the cart.
2. To remove a product from the cart, click on the "Remove" button.
- The information about the removed product will be stored in the database for future reference.

## Design Decisions

#Frontend:

In the Vue.js part of the project, an effective design pattern called Atomic Design
To manage the shopping cart functionality, a dedicated store was implemented using Vuex, a state management library for Vue.js. The store module specifically handles the management of the cart, including adding and removing items, updating quantities, and calculating totals.

#Backend:

In the backend, the project utilizes several design patterns to achieve a structured and maintainable codebase. These design patterns help separate concerns, promote code reusability, and enhance overall system flexibility. Below are the design patterns used in this project:

#Repository Pattern

The Repository pattern is employed to encapsulate data access logic and provide a consistent interface for interacting with the underlying data storage (in this case, a database). The CartRepository class acts as an intermediary between the application's code and the data persistence layer.

#Dependency Injection

The project employs the Dependency Injection pattern to manage the dependencies between classes. In the CartRepository class, the dependencies (Cart, CartItem, and RemovedItem models) are injected through the constructor.

#Observer Pattern

The Observer pattern is applied to the mergeCarts method in the CartRepository class. When merging two carts, the method iterates over the cart items of the old cart and performs actions based on whether corresponding cart items exist in the new cart or not.


I have implemented a specific approach that utilizes a single cart table to store user-related information, including the user ID and session ID. The cart items are stored in a separate table, linked by the cart ID. This approach allows efficient tracking of user carts, even for non-logged-in users, by associating them with a session ID.

While I am aware of an alternative approach that involves introducing a sessions table and a separate carts table, linked by another table, I have chosen to go with the first approach due to its simplicity and efficiency.
