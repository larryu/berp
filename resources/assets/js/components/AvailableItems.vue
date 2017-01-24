<template>
    <div class="modal fade" id="available-item-list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog custom-class" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Available Items</h4>
          </div>
          <div class="modal-body">
            <div class="container">
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
            </div>
          </div>
        </div>
      </div>
    </div>


</template>


<script>
import Vue from 'vue'
import Vuetable from 'vuetable-2/src/components/Vuetable.vue'
import VuetablePagination from 'vuetable-2/src/components/VuetablePagination.vue'
import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo.vue'

Vue.component('cust-action', {
    template: [
        '<div>',
            '<button class="btn btn-primary" @click="itemAction(\'add\', rowData, rowIndex)" title="Add"><i class="glyphicon glyphicon-plus"></i>Add</button>',
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
            console.log('Item Component ready.', this.$route.params.orderId)
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
                url: '/api/available/items',
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        title: 'Item ID',
                        name: 'id',
                        sortField: 'id',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                    },
                    {
                        title: 'Product ID',
                        name: 'product_id',
                        sortField: 'product_id',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                    },
                    {
                        title: 'Product Sku',
                        name: 'product.sku',
                        sortField: 'product.sku',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                    },
                    {
                        title: 'Status',
                        name: 'status',
                        sortField: 'status',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                    },
                    {
                        title: 'Physical Status',
                        name: 'physical_status',
                        sortField: 'physical_status',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                    },
                    {
                        title: 'Actions',
                        name: '__component:cust-action',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                    },
                ],
                sortOrder: [
                    {
                        field: 'id',
                        direction: 'asc'
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
            onActions(data) {
                console.log('onActions', data);
                if (data.action == 'add') {
                    this.addAction(data.data);
                }
            },
            addAction (data)
            {
                console.log('addAction', data);
                data.order_id = this.orderId;
                // add a new item to this order
                this.$http.post('/api/orders/items/add/', data)
                    .then(response => {
                        let alert = {
                            show: true,
                            type: 'success',
                            title: 'Success',
                            message: 'Item successfully created.'
                        };

                        this.$bus.$emit('showAlert', alert);

                        this.resetForm();
                    }).catch(response => {
                        console.log('onSubmit catch error', response);
                        let errors = response.body;

                        this.$bus.$emit('formErrors', errors);
                    });
                $("#av-list").modal('hide');
                this.$parent.$refs.vuetable.refresh();
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
    @media screen and (min-width: 768px) {
        .custom-class {
            width: 80%;
            /* either % (e.g. 60%) or px (400px) */
        }
    }
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