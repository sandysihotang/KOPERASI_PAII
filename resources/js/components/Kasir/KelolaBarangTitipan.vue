<template>
    <div id="app" class="container">
        <b-breadcrumb :items="itemsProduk"></b-breadcrumb>
        <b-container>
            <b-row class="alert" style="background-color: #f8f9fa;">
                <b-form-group>
                    <b-input-group>
                        <b-input-group-append>
                            <button type="button" class="btn btn-success" v-b-modal.modal-1>
                                <font-awesome-icon icon="plus"/>
                                Tambah Titipan
                            </button>
                        </b-input-group-append>
                    </b-input-group>
                </b-form-group>
            </b-row>
            <b-row class="alert" style="background-color: #f8f9fa;">
                <p class="mt-3 alert-primary">Current Page: {{ currentPage }}</p>
                <b-table
                    responsive
                    hover
                    id="mytable"
                    :items="penitip"
                    :fields="fields"
                    :current-page="currentPage"
                    :per-page="perPage"
                    striped>
                    <template slot="aksi" slot-scope="row">
                        <router-link :to="'/detailpenitip/'+row.item.id">
                            <button class="btn btn-light btn-outline-secondary btn-sm" title="Lihat Detail">
                                <font-awesome-icon icon="eye"/>
                            </button>
                        </router-link>
                    </template>
                </b-table>
            </b-row>

            <b-modal id="modal-1" ref="modal" title="Tambah titipan">
                <b-container>
                    <div class="row">
                        <div class="col-md-12 col-md-offset-2">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <b-form-group>
                                        <label>Nama Penitip</label>
                                        <b-input-group>
                                            <input type="text" v-model="namaPenitip" class="form-control" required
                                                   placeholder="Nama Penitip"/>
                                        </b-input-group>
                                    </b-form-group>
                                    <b-button @click.prevent="addPenitip" class="float-right" variant="success"><i
                                        class="fa fa-plus"></i>Tambahkan
                                    </b-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </b-container>
            </b-modal>
            <b-row>
                <b-pagination
                    v-model="currentPage"
                    :total-rows="rows"
                    :per-page="perPage"
                    aria-controls="mytable"
                >
                </b-pagination>
            </b-row>
        </b-container>
    </div>
</template>

<script>
    import BContainer from "bootstrap-vue/src/components/layout/container";
    import BRow from "bootstrap-vue/src/components/layout/row";
    import BCard from "bootstrap-vue/src/components/card/card";
    import BCardText from "bootstrap-vue/src/components/card/card-text";
    import BCardBody from "bootstrap-vue/src/components/card/card-body";
    import moment from 'moment';
    import {CurrencyInput} from 'vue-currency-input'

    export default {
        components: {BCardBody, BCardText, BCard, BRow, BContainer, CurrencyInput},
        data() {
            return {
                namaPenitip: null,
                itemsProduk: [
                    {
                        text: 'Kasir',
                        to: {
                            path: '/kasir'
                        }
                    },
                    {
                        text: 'Kelola Titipan',
                        active: true
                    }
                ],
                fields: [
                    {
                        key: "nama_owner",
                        label: "Nama Penitip",
                        sorttable: true
                    },
                    {
                        key: "id",
                        thClass: 'd-none', tdClass: 'd-none'
                    },
                    {
                        key: "created_at",
                        label: "Tanggal Menitip",
                        sorttable: true,
                        formatter: value => {
                            moment.lang('id')
                            return moment(value).format('dddd, Do MMMM YYYY');
                        }
                    },
                    {
                        key: "aksi",
                        label: "Aksi",
                        sorttable: true
                    }

                ],
                perPage: 10,
                currentPage: 1,
                penitip: []
            }
        },
        methods: {
            addPenitip() {
                if (this.namaPenitip === null) {
                    this.$swal({
                        position: 'center',
                        type: 'error',
                        title: 'Isi Nama Penitip',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    return
                }
                axios.post('api/createNewPenitip', {nama: this.namaPenitip}, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        this.$router.push('/penambahanBarangPenitip/' + e.data.id)
                    })
            },
            getAllPenitip() {
                axios.get('api/allPenitip', {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        this.penitip = e.data
                    })
            }
        },
        created() {
            this.getAllPenitip()
        },
        computed: {
            rows() {
                return this.penitip.length
            }
        }
    }
</script>

<style scoped>
    .c {
        max-width: 80% !important;
        border-radius: 23px;
        max-height: 10% !important;
    }

    table#mytable .flip-list-move {
        transition: transform 1s;
    }
</style>
