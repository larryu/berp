<template>
    <div>
    <form @submit.prevent="submit" novalidate v-cloak>
        <div class="row">
            <div class="col-md-6">
                <div
                    class="form-group"
                    :class="{ 'has-error': errors['order_id'] }">
                    <label class="control-label" for="order_id">
                        Order ID
                    </label>

                    <input
                        v-model="item.order_id"
                        type="text"
                        :disabled="item.order_id ? true : false"
                        class="form-control"
                        id="order_id"
                        placeholder="order id...">

                    <span class="help-block" v-for="error of errors['order_id']">
                        {{ error }}
                    </span>
                </div>

                <div
                    class="form-group"
                    :class="{ 'has-error': errors['product_id'] }">
                    <label class="control-label" for="email">
                        Product
                    </label>
                     <el-select
                        v-model="item.product.sku"
                        filterable
                        remote
                        placeholder="Please enter a sku"
                        v-on:change="onChange"
                        :remote-method="remoteMethod"
                        :loading="loading">
                        <el-option
                          v-for="pitem in skus"
                          :key="pitem.value"
                          :label="pitem.label"
                          :value="pitem.value">
                        </el-option>
                      </el-select>

                    <span class="help-block" v-for="error of errors['product_id']">
                        {{ error }}
                    </span>
                </div>

                <div
                    class="form-group"
                    :class="{ 'has-error': errors['status'] }">
                    <label class="control-label">
                        Status
                    </label>
                    <el-select v-model="item.status" placeholder="Select" v-on:change="onStatusChange">
                        <el-option
                          v-for="status in statuses"
                          :label="status.label"
                          :value="status.value">
                        </el-option>
                    </el-select>
                    <span class="help-block" v-for="error of errors['status']">
                        {{ error }}
                    </span>
                </div>

                <div
                    class="form-group"
                    :class="{ 'has-error': errors['physical_status'] }">
                    <label class="control-label">
                        Physical Status
                    </label>
                    <el-select v-model="item.physical_status" placeholder="Select">
                        <el-option
                          v-for="pstatus in physical_statuses"
                          :key="pstatus.value"
                          :label="pstatus.value"
                          :value="pstatus.value"
                          :disabled="pstatus.disabled">
                        </el-option>
                    </el-select>
                    <span class="help-block" v-for="error of errors['physical_status']">
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
import ElementUI from 'element-ui'
import lang from 'element-ui/lib/locale/lang/en'
import locale from 'element-ui/lib/locale'
locale.use(lang)

    export default {
        components: {ElementUI},
        props: {
            item: {
                type: Object,
                required: true
            },
        },
        mounted() {
            this.$http.get('/products/')
                .then(response => {
                    console.log('mounted response=',response);
                    this.list = response.data.map(item=> {
                        return { id: item.id,  value: item.sku, label: item.sku };
                    });
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
                sku: {},
                skus : [],
                list: [],
                loading: false,
                statuses : [
                            { value: 'Available', label: 'Available'},
                            { value: 'Assigned', label: 'Assigned'}
                           ],
                physical_statuses :
                            [
                            { value: 'To order', label: 'To order', disabled: true},
                            { value: 'In warehouse', label: 'In warehouse'},
                            { value: 'Delivered', label: 'Delivered'},
                            ],
            }
        },
        computed: {

        },
        methods: {
            onStatusChange(selectedValue)
            {
                console.log('onStatusChange selectedValue=', selectedValue);
                if (selectedValue == "Assigned")
                {
                    this.physical_statuses.filter(litem => {
                        if (litem.value != 'Delivered') litem.disabled = true;
                      }
                    );
                }
                else
                {
                    this.physical_statuses.filter(litem => {
                        if (litem.value != 'Delivered') litem.disabled = false;
                      }
                    );
                }
                console.log('phy status',this.physical_statuses);
             },
            onChange(selectedValue)
            {
                console.log('onChange selectedValue=', selectedValue);
            },
            submit() {
                console.log('submit this.item=', this.item);
                console.log('this.list=',this.list);
                let formData = new FormData();
                var selectedSku = this.item.product.sku;
                this.sku = this.list.filter(litem => {
                  if (litem.label == selectedSku) return litem.id;
                });
                console.log('this.sku=',this.sku);
                console.log('this.item.order_id=',this.item.order_id);
                if (!this.item.order_id) this.item.order_id = '';
                formData.set('order_id', this.item.order_id);
                formData.set('product_id', this.sku[0].id);
                formData.set('status', this.item.status);
                formData.set('physical_status', this.item.physical_status);
                console.log('formData=', formData);
                this.$emit('submitted', formData);
            },
             remoteMethod(query) {
                if (query !== '') {
                  this.loading = true;
                  setTimeout(() => {
                    this.loading = false;
                    this.skus = this.list.filter(item => {
                      return item.label.toLowerCase()
                        .indexOf(query.toLowerCase()) > -1;
                    });
                  }, 200);
                } else {
                  this.skus = [];
                }
              }
        },
    }
</script>
