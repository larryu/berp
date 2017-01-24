<template>
    <div class="panel panel-default" v-cloak>
        <div class="panel-body">
            <legend>Create Item</legend>

            <crud-form @submitted="onSubmit" :item="item"></crud-form>

            <div class="text-center mrg-top-1em">
                <button class="btn btn-info"  v-on:click="onBackToItems">
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
                url: '/api/item/create',
                item: {
                    order_id: '',
                    product_id: '',
                    status: '',
                    physical_status: '',
                    product : { sku: ''}
                }
            }
        },
        created() {
            console.log('created: route.params',this.$route.params);
            this.item.order_id = this.$route.params.orderId;
        },
        methods: {
            onSubmit (formData) {
                console.log('onSubmit:',formData);
                this.$http.post(this.url, formData)
                    .then(response => {
                        let alert = {
                            show: true,
                            type: 'success',
                            title: 'Success',
                            message: 'Item successfully created.'
                        };

                        this.$bus.$emit('showAlert', alert);

                        this.resetForm();
                        this.$router.back();
                    }).catch(response => {
                        console.log('onSubmit catch error', response);
                        let errors = response.body;

                        this.$bus.$emit('formErrors', errors);
                    });
            },
            resetForm() {
                this.item = {
                    order_id: '',
                    product_id: '',
                    status: '',
                    physical_status: '',
                    product : { sku: ''}
                }
            },
            onBackToItems() {
                console.log('onBackToItems');
                this.$router.back()
            }
        }
    }
</script>
