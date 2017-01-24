<template>
    <div class="panel panel-default" v-cloak>
        <div class="panel-body">
            <legend>Edit Item</legend>

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
                itemId: this.$route.params.itemId,
                url: '/api/item/',
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
            console.log('this.itemId',this.itemId);
            this.$http.get('/api/item/' + this.itemId)
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
                let url = this.url + this.itemId;
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
