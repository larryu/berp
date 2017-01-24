<template>
    <div>
    <form @submit.prevent="submit" novalidate v-cloak>
        <div class="row">
            <div class="col-md-6">

                <div
                    class="form-group"
                    :class="{ 'has-error': errors['sku'] }">
                    <label class="control-label" for="sku">
                        Product Sku
                    </label>

                    <input
                        v-model="item.sku"
                        type="text"
                        class="form-control"
                        id="sku"
                        placeholder="product sku...">

                    <span class="help-block" v-for="error of errors['sku']">
                        {{ error }}
                    </span>
                </div>


                <div
                    class="form-group"
                    :class="{ 'has-error': errors['colour'] }">
                    <label class="control-label" for="colour">
                        Product Colour
                    </label>

                    <input
                        v-model="item.colour"
                        type="text"
                        class="form-control"
                        id="colour"
                        placeholder="product colour...">

                    <span class="help-block" v-for="error of errors['colour']">
                        {{ error }}
                    </span>
                </div>
            </div>
        </div>

        <div class="row mrg-top-1em">
            <div class="col-md-12 text-center">
                <button class="btn btn-primary">
                    <i class="glyphicon glyphicon-save"></i>
                    Save
                </button>
            </div>
        </div>
    </form>
    <notify :alert="alert"></notify>
    </div>
</template>

<script>


    export default {
        props: {
            item: {
                type: Object,
                required: true
            },
        },
        mounted() {

        },
        created() {

            console.log('crud form created');
            const self = this;
            self.$bus.$on('formErrors', function(errors) {
              console.log(errors);
              self.errors = errors;
            });
            self.$bus.$on('showAlert', function(alert) {
              console.log(alert);
              self.alert = alert;
              self.$emit('notify', self.alert);

              self.errors = {};
            });

        },
        data() {
            return {
                alert: {
                    show: false,
                    type: null,
                    title: null,
                    message: null,
                },
                errors: {},
            }
        },
        computed: {

        },
        methods: {

            submit() {
                console.log('submit this.item=', this.item);
                let formData = new FormData();
                console.log('this.item.sku=',this.item.sku);
                formData.set('sku', this.item.sku);
                formData.set('colour', this.item.colour);
                console.log('formData=', formData);
                this.$emit('submitted', formData);
            },
        },
    }
</script>
