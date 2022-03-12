<template>
  <q-page class="bg-white-3">
      <div class="q-pa-sm panel-header">
      <div class="row">
        <div class="col-sm-6">
          <div class="q-pa-md q-gutter-sm">
            <q-breadcrumbs align="left" style="font-size: 20px">
              <q-breadcrumbs-el label="" icon="home" to="/" />
              <q-breadcrumbs-el label="Index" />
            </q-breadcrumbs>
          </div>
        </div>
        <div class="col-xs-12 col-md-6  text-right">
          <!-- <div class="q-pa-sm q-gutter-sm">
            <q-btn class="bg-primary" style="color: white" icon="add" label="Nuevo" @click="$router.push('/medicine/new')" />
          </div> -->
        </div>
      </div>
    </div>
    <div class="q-pa-md bg-grey-3">
        <div class="row bg-white border-panel">
            <div class="col q-pa-md">
                <div class="row q-col-gutter-xs">
                    <div class="col-xs-12 col-sm-12">
                      <q-table
                      class="my-sticky-header-table"
                      flat
                    bordered
                    :rows="medicine"
                    :columns="columns"
                    row-key="serial"
                    :filter="filter">
                        <template v-slot:top>
                            <div style="width: 100%;">
                                <q-input dense debounce="300" v-model="filter" placeholder="Buscar" @input="v => { filter = v.toUpperCase() }">
                                <template v-slot:append>
                                    <q-icon name="search" />
                                </template>
                                </q-input>
                            </div>
                        </template>
                        <template v-slot:body="props">
                            <q-tr :props="props">
                                <q-td key="descriptions" style="text-align: left;" :props="props">{{ props.row.descriptions }}</q-td>
                                <q-td key="actions" :props="props">
                                <q-btn color="primary" icon="visibility" flat @click="showMedicine(props.row.medicine_id)" size="18px">
                                    <q-tooltip content-class="bg-primary">Editar</q-tooltip>
                                </q-btn>
                                <!-- <q-btn color="red" icon="fas fa-trash-alt" flat @click="deleteSelectedRow(props.row.id)" size="10px">
                                    <q-tooltip content-class="bg-red">Eliminar</q-tooltip>
                                </q-btn> -->
                                </q-td>
                            </q-tr>
                        </template>
                    </q-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <q-dialog v-model="showModel" persistent>
      <q-card style="min-width: 70%; !important;min-height: 80%; ">
        <q-card-section class="bg-red" >
         <div class="row">
           <div class="col-sm-11 text-h6" style="color:white;">Medicamentos</div>
           <div class="col-sm-1 pull-right"><q-btn color="white" flat @click="closeModal()" round dense icon="close" /></div>
         </div>
        </q-card-section>
        <q-separator />
        <q-card-section style="max-height: 60vh" class="scroll">
            <q-table
                      class="my-sticky-header-table"
                      flat
                    bordered
                    :rows="dataMedicine"
                    :columns="columnMedicine"
                    row-key="serial"
                    :filter="filterMedicine">
                        <template v-slot:top>
                            <div style="width: 100%;">
                                <q-input dense debounce="300" v-model="filterMedicine" placeholder="Buscar" @input="v => { filterMedicine = v.toUpperCase() }">
                                <template v-slot:append>
                                    <q-icon name="search" />
                                </template>
                                </q-input>
                            </div>
                        </template>
                        <template v-slot:body="props">
                            <q-tr :props="props">
                                <q-td key="namemedicine" style="text-align: left;" :props="props">{{ props.row.name }}</q-td>
                            </q-tr>
                        </template>
                    </q-table>
        </q-card-section>
        <q-separator />
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import api from 'axios'
export default {
  name: 'Home',
  data () {
    return {
      dataMedicine: [],
      filterMedicine: '',
      showModel: false,
      filter: '',
      medicine: [],
      columns: [
        { name: 'descriptions', align: 'center', label: 'NOMBRE', field: 'descriptions', sortable: true },
        { name: 'actions', align: 'center', label: 'ACCIONES', field: 'actions', sortable: false }
      ],
      columnMedicine: [
        { name: 'namemedicine', align: 'center', label: 'MEDICAMENTO', field: 'namemedicine', sortable: true }
      ]
    }
  },
  created () {
    this.getDescription()
  },
  methods: {
    async showMedicine (id) {
      const id_medicine = id
      this.$q.loading.show()
      await api.get(`http://localhost:8080/getAllDescription/getMedicineOfDescription/${id_medicine}`).then(({ data }) => {
        this.dataMedicine = data.myData
      })
      this.showModel = true
      this.$q.loading.hide()
    },
    closeModal () {
      this.showModel = false
    },
    editSelectedRow (id) {
      this.$router.push(`/medicine/${id}`)
    },
    async deleteSelectedRow (data) {
      const id = data
      this.$q.dialog({
        message: '¿Desea borrar este Medicamento?',
        ok: {
          label: 'Aceptar',
          color: 'green'
        },
        cancel: {
          label: 'Cancelar',
          color: 'red'
        }
      }).onOk(() => {
        this.$q.loading.show()
        api.post(`http://localhost:8080/delete/medicine/${id}`).then(({ data }) => {
          this.$q.notify({
            message: data.message,
            position: 'top',
            color: (data.result ? 'positive' : 'warning')
          })
          if (data.result) {
            this.getMedicine()
          }
        })
      }).onCancel(() => {})
      this.$q.loading.hide()
    },
    async getDescription () {
      this.$q.loading.show()
      await api.get('http://localhost:8080/getAllDescription/getDescription').then(({ data }) => {
        this.medicine = data.myData
        console.log(data.myData)
      })
      this.$q.loading.hide()
    }
  },
}
</script>

<style>

</style>