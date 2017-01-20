
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
import Home      from './components/Home.vue'
import Order      from './components/Order.vue'
import Product    from './components/Product.vue'
import Item       from './components/Item.vue'
import VueRouter  from 'vue-router'
import router     from './router'
import Vue        from 'vue'
import VuePaginator from 'vuejs-paginator'

// Create and mount root instance.
// Make sure to inject the router.
// Route components will be rendered inside <router-view>.
new Vue({

    router,

    components : {
        VPageinator: VuePaginator,
        Home,
        Order,
        Product,
        Item
    },

    data : {

    }

}).$mount('#app')
