<template>
    <div id="app" class="container">
        <b-breadcrumb :items="itemsProduk"></b-breadcrumb>
        <b-container>
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
                        <router-link :to="'/laporan/penitipan/detail/'+row.item.id">
                            <button class="btn btn-light btn-outline-secondary btn-sm" title="Lihat Detail">
                                <font-awesome-icon icon="eye"/>
                            </button>
                        </router-link>
                    </template>
                    <template slot="harga_barang" slot-scope="row">
                        {{ row.item.harga_barang | currency }}
                    </template>
                    <template slot="potongan_pengambilan" slot-scope="row">
                        {{ row.item.potongan_pengambilan}} %
                    </template>
                    <template slot="uang_potongan" slot-scope="row">
                        {{ row.item.harga_barang*row.item.potongan_pengambilan/100 | currency}}
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
                itemsProduk: [
                    {
                        text: 'Dashboard',
                        to: {
                            path: '/dashboard'
                        }
                    },
                    {
                        text: 'Laporan Titipan Selesai',
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
                        key: "harga_barang",
                        label: "Total Terjual",
                        sorttable: true,

                    },
                    {
                        key: "potongan_pengambilan",
                        label: "Potongan",
                        sorttable: true,

                    },
                    {
                        key: "uang_potongan",
                        label: "Uang Potongan",
                        sorttable: true,

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
                        label: "Lihat"
                    }

                ],
                perPage: 10,
                currentPage: 1,
                penitip: []
            }
        },
        methods: {
            getAllPenitip() {
                axios.get('api/allPenitipSelesai', {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
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
