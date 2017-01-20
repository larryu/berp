<template>
    <div class="row">
        <div class="col-md-12">
            <div class="list-group">
                <router-link to="/orders" class="list-group-item ">Orders</router-link>
                <router-link to="/products" class="list-group-item ">Products</router-link>
                <router-link to="/items" class="list-group-item ">Items</router-link>
            </div>
        </div>
    </div>
</template>


<script>

    export default {

        mounted() {

            console.log('Home Component ready.')

        },

        data : function() {
            return {
                orders : []
            }
        },

        methods : {

         }
    }



</script>
