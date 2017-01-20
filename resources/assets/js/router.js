import Home         from './components/Home.vue'
import Order        from './components/Order.vue'
import Product      from './components/Product.vue'
import Item         from './components/Item.vue'
import VueRouter    from 'vue-router'
import Vue          from 'vue'
import VuePaginator from 'vuejs-paginator'

// Use plugin.
// This installs <router-view> and <router-link>,
// and injects $router and $route to all router-enabled child components
Vue.use(VueRouter)

export default new VueRouter({
    mode: 'history',
    base: __dirname,
    routes: [
        { path: '/', component: Home },
        { path: '/products', component: Product },
        { path: '/items', component: Item },
        { path: '/orders', component: Order }
    ]
});
