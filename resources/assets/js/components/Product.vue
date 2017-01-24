<template>
    <div class="container">
        <router-link to="/">Home</router-link>
        <h3>Product List</h3>
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
            <button class="btn btn-success" v-on:click="onCreateProduct">
                <i class="glyphicon glyphicon-plus"></i>Create
            </button>
            <button class="btn btn-info"  v-on:click="onBackToHome">
                <i class="glyphicon glyphicon-chevron-left"></i>
                Back
            </button>
        </div>
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
            console.log('Product Component ready.')
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
                url: '/api/products/',
                paginationPath: '',
                search: '',
                perPage: 5,
                fields: [
                    {
                        title: 'Product ID',
                        name: 'id',
                        sortField: 'id',
                        titleClass: 'text-center col-sm-1',
                        dataClass: 'text-center',
                    },
                    {
                        title: 'Product Sku',
                        name: 'sku',
                        sortField: 'sku',
                        titleClass: 'text-center col-sm-3',
                        dataClass: 'text-center',
                    },
                    {
                        title: 'Colour',
                        name: 'colour',
                        sortField: 'colour',
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
                        field: 'sku',
                        direction: 'asc'
                    }
                ],
                moreParams: {
                    actView: false,
                    actEdit: true,
                    actDelete: false
                }
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
                if (data.action == 'edit') {
                    this.editAction(data.data);
                }
            },
            editAction(data) {
                console.log('editAction');
                this.$router.push({ name: 'editproduct', params: { productId: data.id }})
            },
            onCreateProduct() {
                console.log('onCreateProduct');
                this.$router.push({ name: 'createproduct'})
            },
            onBackToHome() {
                console.log('onBackToHome');
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