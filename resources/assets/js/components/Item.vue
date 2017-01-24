<template>
    <div class="container">
        <router-link to="/">Home</router-link>
        <h3>Items List</h3> <div v-if="orderId">Order ID: {{ orderId }}</div>
        <div class="row">
            <div class="row">
                <div class="dropdown pull-right">
                    <label>Per Page:</label>
                    <select class="form-control dropdown" v-model="perPage">
                            <option :value="2">2</option>
                            <option :value="5">5</option>
                            <option :value="10">10</option>
                            <option :value="25">25</option>
                    </select>
                </div>
            </div>
             <div class="row">
              <vuetable ref="vuetable"
                :api-url="url"
                :css="css"
                :pagination-path="paginationPath"
                :per-page="perPage"
                @CustomAction:action-item="onActions"
                :fields="fields"
                @vuetable:pagination-data="onPaginationData"
                :sort-order="sortOrder"
                :appendParams="moreParams"
              >
              </vuetable>
              </div>
              <div>
                <vuetable-pagination-info ref="paginationInfo"
                  info-class="pull-left""
                ></vuetable-pagination-info>
                <vuetable-pagination ref="pagination"
                  :css="cssPagination"
                  :icons="icons"
                  @vuetable-pagination:change-page="onChangePage"
                >
                </vuetable-pagination>
            </div>
        </div>
        <div class="text-center" v-cloak>
            <button class="btn btn-success" v-on:click="onCreateItem">
                <i class="glyphicon glyphicon-plus"></i>{{ this.orderId ? "Add Item" : "Create" }}
            </button>
            <button class="btn btn-info"  v-on:click="onBackToOrders">
                <i class="glyphicon glyphicon-chevron-left"></i>
                Back
            </button>
        </div>
        <available-list id="av-list" @added="onAdd"></available-list>

    </div>

</template>


<script>
import Vue from 'vue'
import moment from 'moment'
import CustomActions from './CustomActions.vue'
import Vuetable from 'vuetable-2/src/components/Vuetable.vue'
import VuetablePagination from 'vuetable-2/src/components/VuetablePagination.vue'
import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo.vue'

Vue.component('custom-actions', CustomActions)

    export default {

        components: {
            Vuetable,
            VuetablePagination,
            VuetablePaginationInfo
        },
        mounted() {
            console.log('Item Component ready.', this.$route.params.orderId)
        },
        created()
        {

        },
        data() {
            return {
                orderId: this.$route.params.orderId,
                css: {
                    tableClass: 'table table-striped table-bordered table-hover',
                    loadingClass: 'loading',
                    ascendingIcon: 'glyphicon glyphicon-chevron-up',
                    descendingIcon: 'glyphicon glyphicon-chevron-down',
                    sortHandleIcon: 'glyphicon glyphicon-menu-hamburger',
                },
                cssPagination: {
                    wrapperClass: 'pagination pull-right',
                    activeClass: 'btn-primary',
                    disabledClass: 'disabled',
                    pageClass: 'btn btn-border',
                    linkClass: 'btn btn-border',
                },
                icons: {
                    first: '',
                    prev: '',
                    next: '',
                    last: '',
                },
                url: '/api/items/' + this.$route.params.orderId,
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        title: 'Order ID',
                        name: 'order_id',
                        sortField: 'order_id',
                        titleClass: 'text-center col-sm-1',
                        dataClass: 'text-center',
                    },
                    {
                        title: 'Item ID',
                        name: 'id',
                        sortField: 'id',
                        titleClass: 'text-center col-sm-1',
                        dataClass: 'text-center',
                    },
                    {
                        title: 'Product ID',
                        name: 'product_id',
                        sortField: 'product_id',
                        titleClass: 'text-center col-sm-1',
                        dataClass: 'text-center',
                    },
                    {
                        title: 'Product Sku',
                        name: 'product.sku',
                        sortField: 'product.sku',
                        titleClass: 'text-center col-sm-2',
                        dataClass: 'text-center',
                    },
                    {
                        title: 'Status',
                        name: 'status',
                        sortField: 'status',
                        titleClass: 'text-center col-sm-1',
                        dataClass: 'text-center',
                    },
                    {
                        title: 'Physical Status',
                        name: 'physical_status',
                        sortField: 'physical_status',
                        titleClass: 'text-center col-sm-1',
                        dataClass: 'text-center',
                    },
                    {
                        title: 'Actions',
                        name: '__component:custom-actions',
                        titleClass: 'text-center col-sm-1',
                        dataClass: 'text-center',
                    },
                ],
                sortOrder: [
                    {
                        field: 'order_id',
                        direction: 'asc'
                    }
                ],
                moreParams: {
                    actView: false,
                    actEdit: true,
                    actDelete: true
                }
            }
        },
        methods : {
            onAdd() {

            },
            onPaginationData (paginationData) {
              this.$refs.pagination.setPaginationData(paginationData)
              this.$refs.paginationInfo.setPaginationData(paginationData) 
            },
            onChangePage (page) {
              this.$refs.vuetable.changePage(page)
            },
            formatDate (value, fmt = 'D MMM YYYY') {
              return (value == null)
                ? ''
                : moment(value, 'YYYY-MM-DD').format(fmt)
            },
            onActions(data) {
                console.log('onActions', data);
                if (data.action == 'edit') {
                    this.editAction(data.data);
                }
                else if (data.action == 'delete') {
                    this.deleteAction(data.data);
                }
            },
            editAction (data)
            {
                console.log('editAction', data);
                if (this.orderId)
                    this.$router.push({ name: 'orderedititem', params: { orderId: this.orderId, itemId: data.id }})
                else
                    this.$router.push({ name: 'edititem', params: { itemId: data.id }})
            },
            deleteAction (data)
            {
                console.log('deleteAction', data);
                this.$http.post('/api/orders/items/delete/', data)
                    .then(response => {
                        let alert = {
                            show: true,
                            type: 'success',
                            title: 'Success',
                            message: 'Item successfully deleted.'
                        };
                        window.alert('Item successfully deleted.');
                        this.$refs.vuetable.refresh();

                    }).catch(response => {
                        console.log('onSubmit catch error', response);
                        let errors = response.body;
                        window.alert(errors);
                    });
            },
            onCreateItem() {
                console.log('onCreateItem');
                if (this.orderId)
                {
                    //this.$router.push({ name: 'ordercreateitem', params: {orderId: this.orderId}})
                    $("#av-list").modal('show');
                }
                else
                    this.$router.push({ name: 'createitem'})
            },
            onBackToOrders() {
                console.log('onBackToOrders');
                this.$router.back()
            }
        },
        watch: {
            'perPage' (val, oldVal) {
              this.$nextTick(function() {
                this.$refs.vuetable.refresh()
              })
            },
            'paginationComponent' (val, oldVal) {
              this.$nextTick(function() {
                this.$refs.pagination.setPaginationData(this.$refs.vuetable.tablePagination)
              })
            }
        },

    }

</script>
<style>
    .pagination {
      margin-top: 0;
    }
    .btn.btn-border {
      border: 1px solid;
      margin-right: 2px;
    }
    .vuetable-pagination-info {
      margin-top: 8px !important;
    }
</style>