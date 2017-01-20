<template>
    <div class="container">
        <router-link to="/">Home</router-link>
        <h3>Orders List</h3>
        <div class="row">
            <div class="col-md-8">
            <v-paginator :resource.sync="orders" :resource_url="api/orders"></v-paginator>
                <table class="table table-bordered table-hover table-responsive">
                    <thead>
                        <tr>
                            <th>Order Id</th>
                            <th>Customer Name</th>
                            <th>Order Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="order in orders" >
                            <td>{{order.id}}</td>
                            <td>{{order.customer_name}}</td>
                            <td>{{order.order_date}}</td>
                            <td>{{order.status}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>


<script>

    export default {


        mounted() {

            console.log('Order Component ready.')
            this.fetchOrderList()

        },

        data : function() {
            return {
                orders : []
            }

        },

        methods : {

            fetchOrderList : function(){

                    this.$http.get('/api/orders').then((response) => {
                        console.log(response.data);
                        this.$set(this,'orders', response.data.data );

                });
            }
        }

        }

</script>
