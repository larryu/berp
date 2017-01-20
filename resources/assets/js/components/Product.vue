


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
                        this.$route.router.go('orders');

                });
            }
        }

        }

</script>
