
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Notify    from './components/Notify.vue'
import Home      from './components/Home.vue'
import Order      from './components/Order.vue'
import Product    from './components/Product.vue'
import Item       from './components/Item.vue'
import CrudFormItem from './components/CrudFormItem.vue'
import CreateItem   from './components/CreateItem.vue'
import EditItem   from './components/EditItem.vue'
import CrudFormProduct from './components/CrudFormProduct.vue'
import CreateProduct   from './components/CreateProduct.vue'
import EditProduct   from './components/EditProduct.vue'
import AvailableItems from './components/AvailableItems.vue'
import ViewProduct   from './components/ViewProduct.vue'

import router     from './router'
import Vue        from 'vue'
import { alert } from 'vue-strap'

Vue.component('crud-form', CrudFormItem);
Vue.component('crud-form-product', CrudFormProduct);
Vue.component('available-list', AvailableItems);
Vue.component('notify', Notify);
Vue.component('alert', alert);

// define bus
Object.defineProperty(Vue.prototype, '$bus', {
    get() {
        return this.$root.bus;
    }
});

var bus = new Vue({})

// Create and mount root instance.
// Make sure to inject the router.
// Route components will be rendered inside <router-view>.
var app = new Vue({
    router,
    components : {
        Home,
        Order,
        Product,
        Item,
        CrudFormItem,
        CreateItem,
        EditItem,
        CrudFormProduct,
        CreateProduct,
        EditProduct,
        ViewProduct,
        alert,
        AvailableItems,

    },

    data : {
        bus: bus,
    }

}).$mount('#app')
