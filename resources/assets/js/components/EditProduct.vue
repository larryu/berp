<template>
    <div class="panel panel-default" v-cloak>
        <div class="panel-body">
            <legend>Edit Product</legend>

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
                productId: this.$route.params.productId,
                url: '/api/products/edit/',
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

                    this.$bus.$emit('showAlert', alert);
                });
        },
        methods: {
            onSubmit (formData) {
                formData.set('_method', 'POST');
                let url = this.url + this.productId;
                console.log('onSubmit:',formData);
                this.$http.post(url, formData)
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
