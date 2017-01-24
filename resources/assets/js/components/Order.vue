<template>
    <div class="container">
        <router-link to="/">Home</router-link>
        <h3>Orders List</h3>
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
    </div>
</template>


<script>
import Vue from 'vue'
import moment from 'moment'
import Vuetable from 'vuetable-2/src/components/Vuetable.vue'
import VuetablePagination from 'vuetable-2/src/components/VuetablePagination.vue'
import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo.vue'

Vue.component('custom-action', {
    template: [
        '<div>',
            '<button class="btn btn-primary" @click="itemAction(\'view\', rowData, rowIndex)" title="view"><i class="glyphicon glyphicon-zoom-in"></i></button>',
        '</div>'
    ].join(''),
    props: {
        rowData: {
            type: Object,
            required: true
        },
        rowIndex: {
            type: Number
        }
    },
    methods: {
        itemAction: function(action, data, index) {
            console.log('custom-action: ' + action, data, index)
            this.$parent.$emit('CustomAction:action-item', {action: action, data: data, index: index});
        },
    }
})
    export default {

        components: {
            Vuetable,
            VuetablePagination,
            VuetablePaginationInfo
        },
        mounted() {
            console.log('Order Component ready.')
        },

        data() {
            return {
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
                url: '/api/orders',
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        title: 'Order ID',
                        name: 'id',
                        sortField: 'id',
                        titleClass: 'text-center col-sm-1',
                        dataClass: 'text-center',
                    },
                    {
                        title: 'Customer Name',
                        name: 'customer_name',
                        sortField: 'customer_name',
                        titleClass: 'text-center col-sm-3',
                        dataClass: 'text-center',
                    },
                    {
                        title: 'Order Date',
                        name: 'order_date',
                        sortField: 'order_date',
                        callback: 'formatDate|DD-MM-YYYY',
                        titleClass: 'text-center col-sm-1',
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
                        title: 'Actions',
                        name: '__component:custom-action',
                        titleClass: 'text-center col-sm-1',
                        dataClass: 'text-center',
                    },
                ],
                sortOrder: [
                    {
                        field: 'order_date',
                        direction: 'desc'
                    }
                ],
            }
        },
        methods : {
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
            viewAction (data) {
                console.log('viewAction', data);
                this.$router.push({ name: 'orderitems', params: { orderId: data.id } })
            },
            onActions (data) {
                console.log('onActions', data);
                if (data.action == 'view') {
                    this.viewAction(data.data);
                }
            },

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