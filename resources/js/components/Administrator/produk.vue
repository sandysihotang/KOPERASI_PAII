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
                <b-table
                    responsive
                    :items="infoModal.content"
                    :fields="infoModal.fields"
                >
                    <template slot="harga" slot-scope="row">
                        {{ row.item.harga | currency }}
                    </template>
                    <template slot="status_penggunaan" slot-scope="row">
                        {{ successVariant(row) }}
                    </template>
                </b-table>
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

    export default {
        components: {BCardBody, BCardText, BCard, BRow, BContainer},
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
                        text: 'Produk',
                        active: true
                    }
                ],
                produks: [],
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
                    fields:[
                        {
                            key:'harga',
                            label:'Harga',
                            sortable:true
                        },
                        {
                            key:'updated_at',
                            label:'Penggunaan',
                            sortable:true,
                            formatter: value => {
                                moment.lang('id')
                                return moment(value).format('dddd, Do MMMM YYYY');
                            }
                        },
                        {
                            key:'status_penggunaan',
                            label:'Status',
                            sortable:true,
                            formatter: value => {
                                return (value===1?'Harga Beli Sat ini':'')
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
            successVariant(row){
                if (row.item.status_penggunaan===1){
                    row.item._rowVariant='success'
                    return 'Harga Saat ini'
                }
                return ''
            },
            look(row){
                if (row.item.jumlah_barang<5){
                    row.item._rowVariant='danger'
                }
            },
            resetInfoModal(){
                this.infoModal.title=''
                this.infoModal.content=[]
            },
            info(item, index, button) {
                this.infoModal.title = `Harga-harga Beli Barang ` + item.nama
                axios.get("api/hargaBarangItem/" + item.id, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(({data}) => (this.infoModal.content = data))
                this.$root.$emit('bv::show::modal', this.infoModal.id, button)
            },
            loadProduk() {
                axios.get("api/allProduk", {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(({data}) => (this.produks = data))
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
