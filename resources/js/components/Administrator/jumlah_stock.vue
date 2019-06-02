<template>
    <div>
        <b-breadcrumb :items="items"></b-breadcrumb>
    </div>
</template>

<script>
    import BBreadcrumb from "bootstrap-vue/src/components/breadcrumb/breadcrumb";
    export default {
        components: {BBreadcrumb},
        data(){
            return{
                items:[
                    {
                        text:'Dashboard',
                        href:'#'
                    },
                    {
                        text:'Jumlah Stock',
                        href:'#',
                        active:true
                    }
                ]
            }
        }
    }
</script>

<style scoped>

</style>