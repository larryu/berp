<template>
    <alert :show.sync="alert.show" placement="top-right" :duration="3000" :type="alert.type" dismissable>
        <i class="glyphicon glyphicon-exclamation-sign"></i>
        <strong>{{ alert.title }}</strong>
        <p>{{ alert.message }}</p>
    </alert>
</template>

<script>
    export default {
        props: {
            alert: {
                type: Object,
                required: true
            }
        },
        created() {
            const self = this;
            self.$bus.$on('notify', function(alert) {
              console.log(alert);
              self.alert = alert;
            });
        }
    }
</script>