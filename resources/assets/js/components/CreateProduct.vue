<template>
    <div class="panel panel-default" v-cloak>
        <div class="panel-body">
            <legend>Create Product</legend>

            <crud-form-product @submitted="onSubmit" :item="item"></crud-form-product>

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
                url: '/api/products/create',
                item: {
                    sku: '',
                    colour: '',
                }
            }
        },
        created() {
            console.log('created: route.params',this.$route.params);
            //this.item.id = this.$route.params.orderId;
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
                        if (response.status == 403)
                        {
                            let alert = {
                                show: true,
                                type: 'danger',
                                title: 'Error',
                                message: errors,
                            };
                            this.$bus.$emit('showAlert', alert);
                        }
                        else
                            this.$bus.$emit('formErrors', errors);
                    });
            },
            resetForm() {
                this.item = {
                    sku: '',
                    colour: '',
                }
            },
            onBackToItems() {
                console.log('onBackToItems');
                this.$router.back()
            }
        }
    }
</script>
