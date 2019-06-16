<template>
    <div class="container">
        <b-breadcrumb :items="itemDaftarUser"></b-breadcrumb>
        <b-container>
            <h2 align="center">DAFTAR USER</h2>
            <b-row class="alert" style="background-color: #f8f9fa;">
                <b-table
                    responsive
                    :items="allUser"
                    :fields="fields"
                    :current-page="currentPage"
                    :per-page="perPage">
                    <template slot="isAdmin" slot-scope="row">
                        {{ (row.item.isAdmin==1?'Bendahara':row.item.isAdmin==2?'Administrator':'Kasir') }} Koperasi
                    </template>
                    <template slot="aksi" slot-scope="row">
                        <b-button class="btn btn-light btn-outline-secondary btn-sm" @click="setRow(row.item)"
                                  v-b-modal.modal-1>
                            <font-awesome-icon icon="edit"/>
                        </b-button>
                    </template>
                </b-table>
            </b-row>
            <b-row>
                <b-pagination
                    v-model="currentPage"
                    :total-rows="rows"
                    :per-page="perPage"
                    aria-controls="mytable"
                >
                </b-pagination>
            </b-row>

            <b-modal id="modal-1" title="Status" @hide="resetForm" @ok="setStatus">
                <b-container>
                    <b-row>
                        <b-col class="col-md-12">
                            <b-form-group>
                                <label>Status</label>
                                <b-input-group>
                                    <b-form-select v-model="selected.isAdmin" :options="options"></b-form-select>
                                </b-input-group>
                            </b-form-group>
                        </b-col>
                    </b-row>
                </b-container>
            </b-modal>
        </b-container>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                selected: [],
                options: [
                    {value: 0, text: 'kasir'},
                    {value: 1, text: 'Bendahara'},
                    {value: 2, text: 'Administrator'}
                ],
                itemDaftarUser: [
                    {
                        text: 'Dashboard',
                        to: {path: '/dashboardAdministrator'}
                    },
                    {
                        text: 'Daftar User',
                        active: true
                    }
                ],
                allUser: [],
                perPage: 5,
                currentPage: 1,
                fields: [
                    {
                        key: "id",
                        sortable: true,
                        label: "ID",
                    },
                    {
                        key: "name",
                        sortable: true,
                        label: "Nama User",
                    },
                    {
                        key: "email",
                        sortable: true,
                        label: "Email",
                    },
                    {
                        key: "isAdmin",
                        label: "Status",
                    },
                    {
                        key: "aksi",
                        label: "Ubah Status",
                    }
                ]
            }
        },
        methods: {
            fetchData() {
                axios.get('api/getAllUser', {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        this.allUser = e.data
                    })
            },
            setRow(row) {
                this.selected = row
            },
            resetForm() {
                this.fetchData()
                this.selected = []
            },
            setStatus(){
                axios.post('api/setStatusUser',this.selected,{headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e=>{
                        this.fetchData()
                        this.$swal({
                            position: 'center',
                            type: 'success',
                            width:300,
                            title: 'Berhasil Mengubah Status User',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    })
            }
        },
        created() {
            this.fetchData()
        },
        computed: {
            rows() {
                return this.allUser.length
            }
        }
    }
</script>

<style scoped>

</style>
