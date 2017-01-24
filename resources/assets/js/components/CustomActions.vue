<template>
    <div class="custom-actions">
      <button v-if="actView" class="btn btn-primary" @click="itemAction('view', rowData, rowIndex)" title="view"><i class="glyphicon glyphicon-zoom-in"></i></button>
      <button v-if="actEdit" class="btn btn-warning" @click="itemAction('edit', rowData, rowIndex)" title="edit"><i class="glyphicon glyphicon-pencil"></i></button>
      <button v-if="actDelete" class="btn btn-danger" @click="itemAction('delete', rowData, rowIndex)" title="delete"><i class="glyphicon glyphicon-remove"></i></button>
    </div>
  </template>

  <script>
  export default {
    props: {
      rowData: {
        type: Object,
        required: true
      },
      rowIndex: {
        type: Number
      },
    },
    data () {
        return {
          actView: {
            type: Boolean,
            default: true
           },
           actEdit: {
              type: Boolean,
              default: true
           },
           actDelete: {
              type: Boolean,
              default: true
           },
        }
    },
    mounted() {
        console.log('mounted Custom action Component ready.',this.$parent.appendParams);
    },
    created() {
            console.log('created Custom action Component ready.',this.$parent.appendParams);
            this.actView = this.$parent.appendParams.actView;
            this.actDelete = this.$parent.appendParams.actDelete;
            this.actEdit = this.$parent.appendParams.actEdit;
    },
    methods: {
      itemAction (action, data, index) {
        console.log('custom-actions: ' + action, data, index)
        this.$parent.$emit('CustomAction:action-item', {action: action, data: data, index: index});
      }
    }
  }
  </script>

  <style>
    .custom-actions button.ui.button {
      padding: 8px 8px;
    }
    .custom-actions button.ui.button > i.icon {
      margin: auto !important;
    }
  </style>