import Home         from './components/Home.vue'
import Order        from './components/Order.vue'
import Product      from './components/Product.vue'
import Item         from './components/Item.vue'

import CreateItem   from './components/CreateItem.vue'
import EditItem   from './components/EditItem.vue'
import CreateProduct   from './components/CreateProduct.vue'
import EditProduct   from './components/EditProduct.vue'
import ViewProduct   from './components/ViewProduct.vue'
import VueRouter    from 'vue-router'
import Vue          from 'vue'


// Use plugin.
// This installs <router-view> and <router-link>,
// and injects $router and $route to all router-enabled child components
Vue.use(VueRouter)

export default new VueRouter({
    mode: 'history',
    base: __dirname,
    routes: [
        { path: '/', name: 'home', component: Home },

        { path: '/products', name: 'product', component: Product },
        { path: '/products/view/:productId', name: 'viewproduct', component: ViewProduct },
        { path: '/products/create', name: 'createproduct', component: CreateProduct },
        { path: '/products/edit/:productId', name: 'editproduct', component: EditProduct },

        { path: '/items/', name: 'items', component: Item },
        { path: '/items/create', name: 'createitem', component: CreateItem },
        { path: '/items/edit/:itemId', name: 'edititem', component: EditItem },
        
        { path: '/orders', name: 'orders', component: Order },
        { path: '/orders/:orderId/items', name: 'orderitems', component: Item },
        { path: '/orders/:orderId/items/create', name: 'ordercreateitem', component: CreateItem },
         { path: '/orders/:orderId/items/edit/:itemId', name: 'orderedititem', component: EditItem },
    ]
});
