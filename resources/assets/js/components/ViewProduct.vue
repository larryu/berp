<template>
    <div class="panel panel-default" v-cloak>
        <div class="panel-body">
            <legend>View Product</legend>

            <div class="row">
                <div class="col-md-6">

                    <div
                        class="form-group"
                        >
                        <label class="control-label" for="sku">
                            Product Sku
                        </label>

                        <input
                            :disabled="true"
                            v-model="item.sku"
                            type="text"
                            class="form-control"
                            id="sku"
                            placeholder="product sku...">
                    </div>


                    <div
                        class="form-group"
                        >
                        <label class="control-label" for="colour">
                            Product Colour
                        </label>

                        <input
                            :disabled="true"
                            v-model="item.colour"
                            type="text"
                            class="form-control"
                            id="colour"
                            placeholder="product colour...">
                    </div>
                </div>
            </div>

            <div class="text-center mrg-top-1em">
                <button class="btn btn-info"  v-on:click="onBackToHome">
                    <i class="glyphicon glyphicon-chevron-left"></i>
                    Back
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                productId: this.$route.params.productId,
                url: '/products/view/',
                item: {
                    sku: '',
                    colour: '',
                }
            }
        },
        created() {
            console.log('this.productId',this.productId);
            this.$http.get('/api/product/' + this.productId)
                .then(response => {
                    console.log('created response=',response);
                    this.item = response.data;
                })
                .catch(response => {
                    let alert = {
                        show: true,
                        type: 'danger',
                        title: 'Error',
                        message: response.statusText
                    };

                    //this.$bus.$emit('showAlert', alert);
                });
        },
        methods: {
            onBackToHome() {
                console.log('onBackToHome');
                this.$router.push({ name: 'home'});
            }
        }
    }
</script>
