<template xmlns="http://www.w3.org/1999/html">
    <div class="container">
        <b-breadcrumb :items="items"></b-breadcrumb>
        <b-container>
            <b-row>
                <chartjs-bar class="col-md-12" :datalabel="mylabel" :bind="true" :height="height" v-bind:labels="labels"
                             v-bind:datasets="datasets"
                             v-bind:option="option"></chartjs-bar>
            </b-row>
            <h2 align="center" class="mt-5">SELENGKAPNYA</h2>
            <b-row>
                <b-col>
                    <b-row>
                        <b-table
                            responsive
                            :items="allItems"
                            :fields="fields"
                            :current-page="currentPage"
                            :per-page="perPage">
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
                </b-col>
                <b-col>
                    <h3 align="center">Filter</h3>
                    <b-row class="m-2">
                        <b-col md="2">
                            <b-dropdown :text="textOption" variant="primary" class="m-2">
                                <b-dropdown-item href="#" @click="textOption='Harian',statusWaktu=1">Harian
                                </b-dropdown-item>
                                <b-dropdown-item href="#" @click="textOption='Bulanan',statusWaktu=2">Bulanan
                                </b-dropdown-item>
                                <b-dropdown-item href="#" @click="textOption='Tahunan',statusWaktu=3">Tahunan
                                </b-dropdown-item>
                            </b-dropdown>
                        </b-col>
                    </b-row>
                    <b-form @submit.prevent="lihat">
                        <div class="row m-2" v-if="statusWaktu===1">
                            <div class="container">
                                <div class="row">
                                    <b-col>
                                        <label>
                                            Pilih tanggal:
                                        </label>
                                        <input type="number" v-model="harian.hariSampai" class="form-control" min="1"
                                               max="31">
                                    </b-col>
                                </div>
                                <div class="row">
                                    <b-col>
                                        <label>
                                            Bulan:
                                        </label>
                                        <input type="number" v-model="harian.Bulan" class="form-control" min="1"
                                               max="12">
                                    </b-col>
                                </div>
                                <div class="row">
                                    <b-col>
                                        <label>
                                            Tahun:
                                        </label>
                                        <input type="number" v-model="harian.tahun" class="form-control" min="1">
                                    </b-col>
                                </div>
                            </div>
                        </div>
                        <div class="row m-2" v-if="statusWaktu===2">
                            <b-container>
                                <div class="row">
                                    <b-col>
                                        <label>
                                            Bulan:
                                        </label>
                                        <input type="number" v-model="bulanan.bulanSampai" class="form-control" min="1"
                                               max="12">
                                    </b-col>
                                </div>
                                <div class="row">
                                    <b-col>
                                        <label>
                                            Tahun:
                                        </label>
                                        <input type="number" class="form-control" v-model="bulanan.tahun" min="1">
                                    </b-col>
                                </div>
                            </b-container>
                        </div>
                        <div class="row m-2" v-if="statusWaktu===3">
                            <div class="container">
                                <div class="row">
                                    <b-col>
                                        <div class="form-group">
                                            <label>Tahun</label>
                                            <input type="number" v-model="tahunan" class="form-control" min="1">
                                        </div>
                                    </b-col>
                                </div>
                            </div>
                        </div>
                        <b-row class="m-2">
                            <b-col>
                                <button class="btn btn-success m-2" type="submit">Filter</button>
                            </b-col>
                        </b-row>
                    </b-form>
                </b-col>
            </b-row>
        </b-container>
    </div>
</template>
<script>
    import moment from 'moment'

    export default {
        data() {
            return {
                date: null,
                format: null,
                allItems: [],
                perPage: 5,
                currentPage: 1,
                fields: [
                    {
                        key: "nama",
                        sortable: true,
                        label: "Nama Produk",
                    },
                    {
                        key: "jumlah",
                        sortable: true,
                        label: "Jumlah",
                        formatter: value => {
                            return (value!=null?value:0);
                        }
                    }
                ],
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
                textOption: 'Option',
                mylabel: 'TestDataLabel',
                height: 300,
                items: [
                    {
                        text: 'Dashboard',
                        to: {path: '/dashboard'}
                    },
                    {
                        text: 'Pembelian Produk',
                        active: true
                    }
                ],
                labels: [],
                datasets: [{
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                    ],
                    borderWitdh: 1,
                    data: []
                }],
                option: {
                    title: {
                        display: true,
                        position: 'bottom',
                        text: '5 Teratas Produk Yang Paling Sering Dibeli(Buah)',
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            }
        },
        methods: {
            fetchData() {
                this.labels = []
                this.datasets[0].data = []
                axios.get('api/getForMore', {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        for (var i in e.data) {
                            this.labels.push(e.data[i].nama)
                            this.datasets[0].data.push(Number(e.data[i].jumlah))
                        }
                    })
                axios.get('api/getForMoreSelengkapnya', {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        this.allItems = e.data
                    })
            },
            lihat() {
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
                            title: 'Silahkan Pilih waktu',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        return
                    }
                    this.labels = []
                    this.datasets[0].data = []
                    axios.post('api/filterPembelian', {
                        statusWaktu: this.statusWaktu,
                        waktu: this.harian
                    }, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                        .then(e => {
                            for (var i in e.data) {
                                this.labels.push(e.data[i].nama)
                                this.datasets[0].data.push(Number(e.data[i].jumlah))
                            }
                            this.option.title.text = '5 Teratas Produk Yang Paling Sering Dibeli(Buah) Tanggal '
                            moment.lang('id')
                            this.option.title.text = this.option.title.text + ' ' + moment('' + this.harian.tahun + '-' + this.harian.Bulan + '-' + this.harian.hariSampai).format('dddd, Do MMMM YYYY')
                        })
                    axios.post('api/filterPembelianSelengkapnya', {
                        statusWaktu: this.statusWaktu,
                        waktu: this.harian
                    }, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                        .then(e => {
                            this.allItems = e.data
                        })

                    this.statusWaktu = null
                    this.textOption = 'Option'
                } else if (this.statusWaktu === 2) {
                    if (this.bulanan.bulanSampai == null || this.bulanan.tahun == null) {
                        this.$swal({
                            position: 'center',
                            type: 'error',
                            width:300,
                            title: 'Silahkan Pilih waktu',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        return
                    }
                    this.labels = []
                    this.datasets[0].data = []
                    axios.post('api/filterPembelian', {
                        statusWaktu: this.statusWaktu,
                        waktu: this.bulanan
                    }, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                        .then(e => {
                            for (var i in e.data) {
                                this.labels.push(e.data[i].nama)
                                this.datasets[0].data.push(Number(e.data[i].jumlah))
                            }
                            this.option.title.text = '5 Teratas Produk Yang Paling Sering Dibeli(Buah) Bulan '
                            moment.lang('id')
                            this.option.title.text = this.option.title.text + ' ' + moment('' + this.bulanan.tahun + '-' + this.bulanan.bulanSampai).format('MMMM YYYY')
                        })
                    axios.post('api/filterPembelianSelengkapnya', {
                        statusWaktu: this.statusWaktu,
                        waktu: this.bulanan
                    }, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                        .then(e => {
                            this.allItems = e.data
                        })
                    this.statusWaktu = null
                    this.textOption = 'Option'
                } else if (this.statusWaktu === 3) {
                    if (this.tahunan == null) {
                        this.$swal({
                            position: 'center',
                            type: 'error',
                            width:300,
                            title: 'Silahkan Pilih waktu',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        return
                    }
                    this.labels = []
                    this.datasets[0].data = []
                    axios.post('api/filterPembelian', {
                        statusWaktu: this.statusWaktu,
                        waktu: this.tahunan
                    }, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                        .then(e => {
                            for (var i in e.data) {
                                this.labels.push(e.data[i].nama)
                                this.datasets[0].data.push(Number(e.data[i].jumlah))
                            }
                            this.option.title.text = '5 Teratas Produk Yang Paling Sering Dibeli(Buah) Tahun '
                            moment.lang('id')
                            this.option.title.text = this.option.title.text + ' ' + moment('' + this.tahunan ).format('YYYY')
                        })
                    axios.post('api/filterPembelianSelengkapnya', {
                        statusWaktu: this.statusWaktu,
                        waktu: this.tahunan
                    }, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                        .then(e => {
                            this.allItems = e.data
                        })
                    this.statusWaktu = null
                    this.textOption = 'Option'
                }
            }
        },
        created() {
            this.fetchData()
        },
        computed: {
            rows() {
                return this.allItems.length
            }
        }
    }
</script>

<style>
</style>
