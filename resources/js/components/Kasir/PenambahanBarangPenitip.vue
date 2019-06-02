<template>
    <div class="container">
        <b-breadcrumb :items="items"></b-breadcrumb>
        <div class="container alert" style="background-color: #f8f9fa;">
            <div class="col-md-12 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row float-right mb-2">
                            <b-button v-if="!datas.length && !createExcelShow" disabled class="btn btn-primary mr-1">
                                <font-awesome-icon icon="download"/>
                                Simpan Data
                            </b-button>
                            <b-button v-else-if="datas.length" class="btn btn-primary mr-1" @click="simpanData">
                                <font-awesome-icon icon="download"/>
                                Simpan Data
                            </b-button>
                            <b-button v-else-if="createExcelShow" class="btn btn-primary mr-1" disabled>
                                <b-spinner small type="grow"></b-spinner>
                                Saving...
                            </b-button>
                            <b-button v-b-modal.modal-1 class="btn btn-success">
                                <font-awesome-icon icon="plus-circle"/>
                                Tambah
                            </b-button>
                        </div>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center">Nama</th>
                                <th scope="col" class="text-center">Kode Barang</th>
                                <th scope="col" class="text-center">Harga /buah</th>
                                <th scope="col" class="text-center">Qty</th>
                                <th scope="col" class="text-center">Total Harga</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="data in datas" :key="data.id">
                                <td class="text-center">{{ data.nama_barang }}</td>
                                <td class="text-center">{{ data.code_barang }}</td>
                                <td class="text-center">{{ data.harga_barang | currency}}</td>
                                <td class="text-center">{{ data.jumlah_barang }}</td>
                                <td class="text-center">{{ data.harga_barang*data.jumlah_barang | currency}}</td>
                                <td class="text-center">
                                    <i style="cursor: pointer;" class="btn btn-warning btn-sm">
                                        <font-awesome-icon icon="edit" @click="edit(data.id)"/>
                                    </i>
                                    <i style="cursor: pointer;" class="btn btn-danger btn-sm">
                                        <font-awesome-icon icon="calendar-times" @click="deleteBarang(data.id)"/>
                                    </i>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-center"><b>Total Seluruhnya</b></td>
                                <td colspan="2" class="text-center"><b>{{ totalBarang | currency }}</b></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <b-modal id="modal-1" ref="modal1" title="Tambah Produk" hide-footer>
                <form_penambahan_titipan v-on:refresh="refreshData" ref="form_input"></form_penambahan_titipan>
            </b-modal>
        </div>
        <div id="invoice">
            <div v-for="bar in barcode">
                <label>{{ bar.nama_barang }}</label>
                <barcode v-bind:value="bar.code_barang">
                    Show this if the rendering fails.
                </barcode>
            </div>
        </div>
    </div>
</template>

<script>
    import barcode from 'vue-barcode'
    export default {
        components: {
            'barcode': barcode
        },
        data() {
            return {
                barcode:[],
                kategori: null,
                createExcelShow: false,
                items: [
                    {
                        text: 'Dashboard',
                        to: {path: '/dashboard'}
                    },
                    {
                        text: 'Kelola Titipan',
                        to: {path: '/Kelolatitipan'}
                    },
                    {
                        text: 'Penambahan Barang Titipan',
                        active: true
                    }
                ],
                dataKey: {
                    harga: null,
                    jumlah: null
                },
                datas: [],
                totalHarga: [],
                totalBarang: 0
            }
        },
        methods: {
            print(){
                const modal = document.getElementById("invoice")
                const cloned = modal.cloneNode(true)
                let section = document.getElementById("print")

                if (!section) {
                    section  = document.createElement("div")
                    section.id = "print"
                    document.body.appendChild(section)
                }

                section.innerHTML = "";
                section.appendChild(cloned);
                window.print();
            },
            deleteBarang(id) {
                axios.delete('api/deleteTitipan/' + id, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        this.refreshData()
                        const Toast = this.$swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        Toast.fire({
                            type: 'success',
                            title: 'Barang Berhasil Dihapus'
                        })
                    })
            },
            edit(id) {
                axios.get('api/getTitipan/' + id, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(async e => {
                        this.dataKey.harga = e.data.harga_barang
                        this.dataKey.jumlah = e.data.jumlah_barang
                        const {value: formValues} = await this.$swal.fire({
                            title: 'Edit Jumlah Dan Harga',
                            html:
                                '<label for="swal-input1" style="font-size:25px;">Harga: </label><br><input id="swal-input1" type="number" class="form-group swal2-input" placeholder="Harga" value="' + this.dataKey.harga + '"><br>' +
                                '<label for="swal-input2" style="font-size:25px;">Jumlah:</label><br><input id="swal-input2" type="number" class="form-group swal2-input" placeholder="Jumlah" value="' + this.dataKey.jumlah + '">',
                            focusConfirm: false,
                            preConfirm: () => {
                                return [{
                                    harga: document.getElementById('swal-input1').value,
                                    jumlah: document.getElementById('swal-input2').value
                                }]
                            }
                        })
                        if (formValues.length > 0) {
                            this.createExcelShow = true
                            axios.put('api/editTitipan/' + id, formValues, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                                .then(e => {
                                    this.createExcelShow = false
                                    this.refreshData()
                                    const Toast = this.$swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Barang Berhasil Diubah'
                                    })
                                })
                        }
                    })
            },
            fetchData() {
                axios.get('api/getBarangTitipan/' + this.$route.params.id, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(({data}) => (this.datas = data))
            },
            getTotal() {
                axios.get('api/getTotalTitipan/' + this.$route.params.id, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        console.log(e)
                        if (e.data[0]) {
                            var sum = 0
                            for (var i = 0; i < e.data.length; i++) {
                                sum += (e.data[i].harga_barang * e.data[i].jumlah_barang)
                            }
                            this.totalBarang = sum
                        } else {
                            this.totalBarang = 0
                        }
                    })
            },
            refreshData() {
                this.getTotal()
                this.fetchData()
                this.$nextTick(() => {
                    this.$refs.modal1.hide()
                })
            },
            simpanData() {
                axios.get('api/simpanData/' + this.$route.params.id, {headers: {Authorization: `Bearer ${this.$auth.getToken()}`}})
                    .then(e => {
                        this.barcode=e.data
                        const Toast = this.$swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        Toast.fire({
                            type: 'success',
                            title: 'Berhasil Menyimapan Barang'
                        })
                        this.refreshData()
                        setTimeout(()=>this.print(),3000)
                    })
            }
        },
        created() {
            this.refreshData()
        }
    }
</script>

<style>
    @media screen {
        #print {
            display: none;
        }
    }

    @media print {
        body * {
            visibility:hidden;
        }
        #print, #print * {
            visibility:visible;
        }
        #print {
            position:absolute;
            left:0;
            top:0;
        }
    }
</style>
