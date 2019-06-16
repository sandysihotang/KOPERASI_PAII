<template>
    <div id="app" class="container">
        <b-breadcrumb :items="itemsProduk"></b-breadcrumb>
        <b-container>
            <b-row class="alert" style="background-color: #f8f9fa;">
                <b-form-group>
                    <b-input-group>
                        <b-form-input v-model="filter" placeholder="Cari Produk"></b-form-input>
                        <b-input-group-append>
                            <b-button :disabled="!filter" @click="filter=''">Bersihkan</b-button>
                        </b-input-group-append>
                    </b-input-group>
                </b-form-group>
                <b-form-group>
                    <b-input-group>
                        <b-input-group-append>
                            <button type="button" v-b-modal.modal-1 class="btn btn-success"><!--@click="pemasukanBarang"-->
                                <font-awesome-icon icon="plus"/>
                                Tambah Pemasukan Barang
                            </button>
                            <b-modal id="modal-1" ref="modal1" title="Tambah Produk" hide-footer>
                                <form_suplier></form_suplier>
                            </b-modal>
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
                    :items="produks"
                    :fields="fields"
                    :filter="filter"
                    :current-page="currentPage"
                    :per-page="perPage"
                    striped>
                    <template slot="harga_jual" slot-scope="row">
                        <b-row>
                            <b-col>
                                {{ row.item.harga_jual | currency }}
                                {{ look(row) }}
                            </b-col>
                            <b-col>
                                <button class="btn btn-light btn-outline-secondary btn-sm"
                                        @click="info(row.item, row.index, $event.target)">
                                    <font-awesome-icon icon="eye"/>
                                </button>
                            </b-col>
                        </b-row>
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
            <b-modal :id="infoModal.id" :title="infoModal.title" @hide="resetInfoModal">
                <b-container>
                    <b-row>
                        <b-table
                            responsive
                            :items="infoModal.content"
                            :fields="infoModal.fields">
                            <template slot="harga" slot-scope="row">
                                {{ row.item.harga | currency }}
                            </template>
                            <template slot="status_penggunaan" slot-scope="row">
                                {{ successVariant(row) }}
                            </template>
                        </b-table>
                    </b-row>
                    <b-row>
                        <b-form-group>
                            <label>Harga Jual</label>
                            <b-input-group v-show="!ubahHarga">
                                <input type="text" class="form-control" :value="harga_jual | currency" disabled/>
                                <b-input-group-append>
                                    <b-button @click="ubahHarga = true">Ubah Harga</b-button>
                                </b-input-group-append>
                            </b-input-group>
                            <b-input-group v-show="ubahHarga">
                                <CurrencyInput
                                    class="form-control"
                                    v-model="harga_jual"
                                    :currency="currency_input.currency"
                                    :locale="currency_input.locale"
                                    :min="1"
                                />
                                <b-input-group-append>
                                    <b-button @click="perubahanHarga()">Ubah Harga</b-button>
                                </b-input-group-append>
                            </b-input-group>
                        </b-form-group>
                    </b-row>
                </b-container>
            </b-modal>
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
                ubahHarga: false,
                currency_input: {
                    locale: 'id',
                    currency: 'IDR',
                },
                harga_jual: null,
                itemsProduk: [
                    {
                        text: 'Dashboard',
                        to: {
                            path: '/kasir'
                        }
                    },
                    {
                        text: 'Produk',
                        active: true
                    }
                ],
                produks: [],
                idUbahHarga:null,
                modalShow: false,
                fields: [
                    {
                        key: "nama",
                        sortable: true, label: "Nama Produk"
                    },
                    {
                        key: "id",
                        thClass: 'd-none', tdClass: 'd-none'
                    },
                    {
                        key: "nama_kategori",
                        sortable: true, label: "Kategori"
                    },
                    {
                        key: "code_produk",
                        sortable: true, label: "Kode Produk"
                    },
                    {
                        key: "harga_jual",
                        sortable: true,
                        label: "Harga Perbuah",
                        formatter: value => {
                            let val = (value / 1).toFixed(2).replace('.', ',')
                            return 'Rp. ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                        }
                    },
                    {key: "jumlah_barang", label: "Jumlah"},
                    {
                        key: "updated_at",
                        label: "Diubah Pada Tanggal",
                        sortable: true,
                        formatter: value => {
                            moment.lang('id')
                            return moment(value).format('dddd, Do MMMM YYYY');
                        }
                    }
                ],
                perPage: 10,
                currentPage: 1,
                filter: null,
                infoModal: {
                    id: 'info-modal',
                    title: '',
                    fields: [
                        {
                            key: 'harga',
                            label: 'Harga',
                            sortable: true
                        },
                        {
                            key: 'created_at',
                            label: 'Penggunaan',
                            sortable: true,
                            formatter: value => {
                                moment.lang('id')
                                return moment(value).format('dddd, Do MMMM YYYY');
                            }
                        },
                        {
                            key: 'status_penggunaan',
                            label: 'Status',
                            sortable: true,
                            formatter: value => {
                                return (value === 1 ? 'Harga Beli Baru ini' : '')
                            }
                        }
                    ],
                    content: []
                }
            }
        },
        created() {
            this.loadProduk()
        },
        methods: {
            perubahanHarga() {
                if (this.harga_jual === null) {
                    this.$swal({
                        position: 'center',
                        type: 'error',
                        width:300,
                        title: 'Isi Harga Barang',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    return
                }
                axios.post('api/ubahHargaJual/'+this.idUbahHarga,{harga:this.harga_jual},{headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e=>{
                        this.$swal({
                            position: 'center',
                            type: 'success',
                            width:300,
                            title: 'Berhasil Mengubah Harga Barang',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.loadProduk()
                    })
            },
            successVariant(row) {
                if (row.item.status_penggunaan === 1) {
                    row.item._rowVariant = 'success'
                    return 'Harga Beli Saat ini'
                }
                return ''
            },
            look(row) {
                if (row.item.jumlah_barang < 5) {
                    row.item._rowVariant = 'danger'
                }
            },
            resetInfoModal() {
                this.ubahHarga = false
                this.infoModal.title = ''
                this.infoModal.content = []
            },
            info(item, index, button) {
                this.infoModal.title = `Harga-harga Beli Barang ` + item.nama
                this.idUbahHarga=item.id
                axios.get("api/hargaBarangItem/" + item.id, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(({data}) => (this.infoModal.content = data))
                axios.get('api/getHargaJual/' + item.id, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e=>{
                        this.harga_jual=e.data.harga_jual
                    })
                this.$root.$emit('bv::show::modal', this.infoModal.id, button)
            },
            loadProduk() {
                axios.get("api/allProduk", {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(({data}) => (this.produks = data))
            },
            pemasukanBarang() {
                this.$router.push('/inventory/pemasukan')
            },
            moment
        },
        computed: {
            rows() {
                return this.produks.length
            },
            sortOptions() {
                return this.fields.filter(f => f.sortable)
                    .map(f => {
                        return {text: f.label, value: f.key}
                    })
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
