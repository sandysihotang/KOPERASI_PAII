<template>
    <div class="container">
        <b-breadcrumb :items="itemsKonfirmation"></b-breadcrumb>
        <b-container class="alert" style="background-color: #f8f9fa;">
            <b-row>
                <b-table
                :fields="fields"
                :items="items"
                responsive>
                    <template slot="konfirmation" slot-scope="row">
                        <button class="btn btn-light btn-outline-secondary btn-sm"
                        @click="konfirmasiUser(row.item.id)">
                            <font-awesome-icon icon="check-square"/>
                        </button>
                    </template>
                </b-table>
            </b-row>
        </b-container>
    </div>
</template>

<script>
    import moment from 'moment'
    export default {
        data(){
            return {
                itemsKonfirmation: [
                    {
                        text: 'Dashboard',
                        to: {
                            path: '/dashboard'
                        }
                    },
                    {
                        text: 'Konfirmasi',
                        active: true
                    }
                ],
                fields:[
                    {
                        key: "name",
                        sortable: true,
                        label: "Nama"
                    },
                    {
                        key: "email",
                        sortable: true,
                        label: "Email"
                    },
                    {
                        key: "created_at",
                        sortable: true,
                        label: "Request",
                        formatter: value => {
                            moment.lang('id')
                            return moment(value).format('dddd, Do MMMM YYYY');
                        }
                    },
                    {
                        key: "konfirmation",
                        sortable: true,
                        label: "Konfirmasi"
                    }
                ],
                items:[]
            }
        },
        methods:{
            fetchUserKonfimation(){
                axios.get('api/getUserKonfirmation',{headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(({data})=>(this.items=data))
            },
            konfirmasiUser(row){
                axios.get('api/konfirmasi/'+row,{headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e=>{
                        const Toast = this.$swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000
                        });
                        Toast.fire({
                            type: 'success',
                            title: 'Success'
                        })
                        this.fetchUserKonfimation()
                    })
            }
        },
        created() {
            this.fetchUserKonfimation()
        }
    }
</script>

<style scoped>

</style>
