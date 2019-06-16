<template>
    <div id="app" class="container">
        <b-breadcrumb :items="itemsProduk"></b-breadcrumb>
        <b-container>
            <b-form @submit.prevent="downloadFileExcel">
                <b-row>
                    <b-col md="2">
                        <b-dropdown :text="textOption" variant="primary" class="m-2" style="width:120px">
                            <b-dropdown-item href="#" @click="textOption='Harian',statusWaktu=1">Harian
                            </b-dropdown-item>
                            <b-dropdown-item href="#" @click="textOption='Bulanan',statusWaktu=2">Bulanan
                            </b-dropdown-item>
                            <b-dropdown-item href="#" @click="textOption='Tahunan',statusWaktu=3">Tahunan
                            </b-dropdown-item>
                        </b-dropdown>
                    </b-col>
                    <b-col md="10" v-if="statusWaktu===1">
                        <b-container>
                            <div class="row">
                                <div class="col-md-1">
                                    Tanggal:
                                </div>
                                <div class="col-md-2">
                                    <input type="number" v-model="harian.hariSampai" class="form-control" min="1"
                                           max="31">
                                </div>
                                <div class="col-md-1">
                                    Bulan:
                                </div>
                                <div class="col-md-2">
                                    <input type="number" v-model="harian.Bulan" class="form-control" min="1" max="12">
                                </div>
                                <div class="col-md-1">
                                    Tahun:
                                </div>
                                <div class="col-md-2">
                                    <input type="number" v-model="harian.tahun" class="form-control" min="1">
                                </div>
                            </div>
                        </b-container>
                    </b-col>
                    <b-col md="10" v-if="statusWaktu===2">
                        <b-container>
                            <div class="row">
                                <div class="col-md-1">
                                    Bulan:
                                </div>
                                <div class="col-md-2">
                                    <input type="number" v-model="bulanan.bulanSampai" class="form-control" min="1"
                                           max="12">
                                </div>

                                <div class="col-md-1">
                                    Tahun:
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control" v-model="bulanan.tahun" min="1">
                                </div>
                            </div>
                        </b-container>
                    </b-col>
                    <b-col md="10" v-if="statusWaktu===3">
                        <b-container>
                            <div class="row">
                                <div class="col-md-1">
                                    Tahun:
                                </div>
                                <div class="col-md-2">
                                    <input type="number" v-model="tahunan" class="form-control" min="1">
                                </div>
                            </div>
                        </b-container>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col>
                        <button class="btn btn-success m-2" type="submit" style="width:120px">
                            Export Excel
                        </button>
                    </b-col>
                </b-row>
            </b-form>
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
                harian: {
                    hariSampai: null,
                    Bulan: null,
                    tahun: null
                },
                bulanan: {
                    bulanSampai: null,
                    tahun: null
                },
                tahunan: null,
                statusWaktu: null,
                textOption: "Opsi",
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
            },

            downloadFileExcel() {
                if (this.statusWaktu == null) {
                    this.$swal({
                        position: 'center',
                        type: 'error',
                        width:300,
                        title: 'Silahkan Pilih Waktu',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    return
                } else if (this.statusWaktu === 1) {
                    if (this.harian.hariSampai == null || this.harian.Bulan == null || this.harian.tahun == null) {
                        this.$swal({
                            position: 'center',
                            type: 'error',
                            width:300,
                            title: 'Silahkan Pilih waktu export',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        return
                    }

                    axios.post('api/downloadPenitipanExcel', {
                        id: this.statusWaktu,
                        hariSampai: this.harian.hariSampai,
                        bulan: this.harian.Bulan,
                        tahun: this.harian.tahun
                    }, {
                        headers: {Authorization: `Bearer ${this.$auth.getToken()}`},
                        responseType: 'arraybuffer'
                    })
                        .then(e => {
                            this.downloadFile(e, 'Titipan' + Date.now())
                        })
                } else if (this.statusWaktu === 2) {
                    if (this.bulanan.bulanSampai == null || this.bulanan.tahun == null) {
                        this.$swal({
                            position: 'center',
                            type: 'error',
                            width:300,
                            title: 'Silahkan Pilih waktu export',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        return
                    }
                    axios.post('api/downloadPenitipanExcel', {
                        id: this.statusWaktu,
                        bulanSampai: this.bulanan.bulanSampai,
                        tahun: this.bulanan.tahun
                    }, {
                        headers: {Authorization: `Bearer ${this.$auth.getToken()}`},
                        responseType: 'arraybuffer'
                    })
                        .then(e => {
                            this.downloadFile(e, 'Titipan' + Date.now())
                        })
                } else if (this.statusWaktu === 3) {
                    if (this.tahunan == null) {
                        this.$swal({
                            position: 'center',
                            type: 'error',
                            width:300,
                            title: 'Silahkan Pilih waktu export',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        return
                    }

                    axios.post('api/downloadPenitipanExcel', {
                        id: this.statusWaktu,
                        tahun: this.tahunan
                    }, {
                        headers: {Authorization: `Bearer ${this.$auth.getToken()}`},
                        responseType: 'arraybuffer'
                    })
                        .then(e => {
                            this.downloadFile(e, 'Titipan' + Date.now())
                        })
                }
            },
            downloadFile(response, filename) {
                // It is necessary to create a new blob object with mime-type explicitly set
                // otherwise only Chrome works like it should
                var newBlob = new Blob([response.data], {type: 'application/excel'})

                // IE doesn't allow using a blob object directly as link href
                // instead it is necessary to use msSaveOrOpenBlob
                if (window.navigator && window.navigator.msSaveOrOpenBlob) {
                    window.navigator.msSaveOrOpenBlob(newBlob)
                    return
                }

                // For other browsers:
                // Create a link pointing to the ObjectURL containing the blob.
                const data = window.URL.createObjectURL(newBlob)
                var link = document.createElement('a')
                link.href = data
                link.download = filename + '.xlsx'
                link.click()
                setTimeout(function () {
                    // For Firefox it is necessary to delay revoking the ObjectURL
                    window.URL.revokeObjectURL(data)
                }, 100)
                this.$swal({
                    position: 'center',
                    type: 'success',
                    width:300,
                    title: 'Berhasil Mengexport Data',
                    showConfirmButton: false,
                    timer: 1500
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
